<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use Carbon\Carbon;
use DateTimeInterface;
use DragonCode\Cache\Concerns\Call;
use DragonCode\Cache\Concerns\Has;

use function abs;
use function config;
use function get_class;

class Ttl
{
    use Call;
    use Has;

    protected int $default = 3600;

    public function fromMinutes($minutes): int
    {
        return $this->get($minutes, 60);
    }

    public function fromSeconds($seconds): int
    {
        return $this->get($seconds);
    }

    public function fromDateTime(DateTimeInterface $dateTime): int
    {
        $seconds = Carbon::now()->diffInSeconds($dateTime);

        return $this->correct($seconds);
    }

    protected function get($value, int $multiplier = 1): int
    {
        if ($this->hasDateTime($value)) {
            return $this->fromDateTime($value);
        }

        if ($config = $this->fromConfig($value)) {
            return $this->correct($config, $multiplier);
        }

        if ($this->hasClosure($value)) {
            $value = $this->call($value);
        }

        if ($this->hasObject($value)) {
            $value = $this->defaultTtl();
        }

        return $this->correct($value, $multiplier);
    }

    protected function correct($value, int $multiplier = 1): int
    {
        $value = (int) $value ?: $this->defaultTtl();

        return abs($value) * $multiplier;
    }

    protected function fromConfig($value): ?int
    {
        $value = $this->resolveClass($value);

        return $this->configTtl($value);
    }

    protected function resolveClass($value): string
    {
        return $this->hasObject($value) ? get_class($value) : (string) $value;
    }

    protected function configTtl(string $value): ?int
    {
        return config('cache.ttl.' . $value);
    }

    protected function defaultTtl(): int
    {
        return config('cache.ttl_default', $this->default);
    }
}
