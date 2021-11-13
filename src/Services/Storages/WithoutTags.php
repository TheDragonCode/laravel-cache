<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use Illuminate\Support\Facades\Cache;

class WithoutTags extends Store
{
    public function get(string $key, callable $default = null)
    {
        if ($this->has($key)) {
            return Cache::get($key);
        }

        return $this->call($default);
    }

    public function put(string $key, callable $callback, int $seconds)
    {
        return Cache::remember($key, $seconds, $callback);
    }

    public function forget(string $key): void
    {
        Cache::forget($key);
    }

    public function has(string $key): bool
    {
        return Cache::has($key);
    }
}
