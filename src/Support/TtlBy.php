<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Cache\Facades\Support\Ttl as TtlSupport;
use DragonCode\Contracts\Cache\Ttl as TtlContract;
use DragonCode\Support\Facades\Helpers\Instance;
use DragonCode\Support\Facades\Helpers\Is;

class TtlBy
{
    protected $default = 3600;

    public function get($value, bool $is_minutes = true): int
    {
        if ($this->isObject($value) && $this->isContract($value)) {
            return $this->correct($value->cacheTtl(), $is_minutes);
        }

        return $this->correct($this->fromConfig($value), $is_minutes);
    }

    protected function fromConfig($value): int
    {
        $value = $this->resolve($value);

        return $this->ttl($value) ?: $this->ttlDefault();
    }

    /**
     * @param  \DateTimeInterface|string|int|callable  $value
     * @param  bool  $is_minutes
     *
     * @return int
     */
    protected function correct($value, bool $is_minutes): int
    {
        return $is_minutes
            ? TtlSupport::fromMinutes($value)
            : TtlSupport::fromSeconds($value);
    }

    protected function ttl(string $value): ?int
    {
        return config('cache.ttl.' . $value);
    }

    protected function ttlDefault(): int
    {
        return config('cache.ttl_default', $this->default);
    }

    protected function resolve($value): string
    {
        return $this->isObject($value) ? get_class($value) : (string) $value;
    }

    protected function isObject($value): bool
    {
        return Is::object($value);
    }

    protected function isContract($value): bool
    {
        return Instance::of($value, TtlContract::class);
    }
}
