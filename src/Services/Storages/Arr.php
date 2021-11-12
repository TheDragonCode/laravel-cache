<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

class Arr extends Store
{
    public function get(string $key, $default = null)
    {
        return $default;
    }

    public function put(string $key, $value, int $seconds)
    {
        return $value;
    }

    public function forget(string $key): void
    {
        //
    }
}
