<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

class Disabled extends Store
{
    public function get(string $key, $default = null): mixed
    {
        return $this->call($default);
    }

    public function put(string $key, $value, int $seconds): mixed
    {
        return $this->get($key, $value);
    }

    public function flexible(string $key, $value, int $seconds, int $interval): mixed
    {
        return $this->get($key, $value);
    }

    public function remember(string $key, $value, int $seconds): mixed
    {
        return $this->get($key, $value);
    }

    public function rememberForever(string $key, $value): mixed
    {
        return $this->get($key, $value);
    }

    public function forget(string $key): void {}

    public function has(string $key): bool
    {
        return false;
    }

    public function flush(): void {}
}
