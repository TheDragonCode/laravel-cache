<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

class Disabled extends Store
{
    public function get(string $key, $default = null)
    {
        return $this->call($default);
    }

    public function put(string $key, $value, int $seconds)
    {
        return $this->get($key, $value);
    }

    public function remember(string $key, $value, int $seconds)
    {
        return $this->get($key, $value);
    }

    public function forget(string $key): void
    {
    }

    public function has(string $key): bool
    {
        return false;
    }
}
