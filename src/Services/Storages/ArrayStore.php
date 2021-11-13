<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

class ArrayStore extends Store
{
    public function get(string $key, callable $default = null)
    {
        return $this->call($default);
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
