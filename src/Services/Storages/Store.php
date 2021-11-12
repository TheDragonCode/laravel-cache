<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use DragonCode\Contracts\Cache\Store as BaseStore;
use DragonCode\Support\Concerns\Makeable;
use Illuminate\Support\Facades\Cache;

abstract class Store implements BaseStore
{
    use Makeable;

    public function get(string $key, $default = null)
    {
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        return $default;
    }

    public function forget(string $key): void
    {
        Cache::forget($key);
    }
}
