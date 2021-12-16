<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Cache\Facades\Support\Ttl as TtlSupport;
use DragonCode\Support\Facades\Helpers\Is;

class TtlBy
{
    protected $default = 3600;

    public function get($value): int
    {
        $value = $this->resolve($value);

        return $this->ttl($value) ?: $this->ttlDefault();
    }

    protected function correct(int $seconds): int
    {
        return TtlSupport::fromSeconds($seconds);
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
}
