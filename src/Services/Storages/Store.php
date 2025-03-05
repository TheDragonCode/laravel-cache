<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use DragonCode\Cache\Concerns\Call;
use DragonCode\Support\Concerns\Makeable;

abstract class Store
{
    use Call;
    use Makeable;

    abstract public function flexible(string $key, $value, int $seconds, int $interval): mixed;

    abstract public function flush(): void;

    abstract public function forget(string $key): void;

    abstract public function get(string $key, $default = null): mixed;

    abstract public function has(string $key): bool;

    abstract public function put(string $key, $value, int $seconds): mixed;

    abstract public function remember(string $key, $value, int $seconds): mixed;

    abstract public function rememberForever(string $key, $value): mixed;

    public function doesntHave(string $key): bool
    {
        return ! $this->has($key);
    }
}
