<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use Illuminate\Support\Facades\Cache;

class MainStore extends Store
{
    public function get(string $key, $default = null)
    {
        if ($this->has($key)) {
            return Cache::get($key);
        }

        return $default;
    }

    public function put(string $key, $value, int $seconds)
    {
        $value = $this->call($value);

        Cache::put($key, $value, $seconds);

        return $value;
    }

    public function remember(string $key, $value, int $seconds)
    {
        $value = $this->makeCallable($value);

        return Cache::remember($key, $seconds, $value);
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
