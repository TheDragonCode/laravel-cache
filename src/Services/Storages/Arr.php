<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

class Arr extends Store
{
    public function get(string $key, callable $default = null)
    {
        return $default();
    }

    public function put(string $key, callable $callback, int $seconds)
    {
        return $this->get($key, $callback);
    }

    public function forget(string $key): void
    {
        //
    }

    public function has(string $key): bool
    {
        return false;
    }
}
