<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use DragonCode\Cache\Concerns\Call;
use DragonCode\Contracts\Cache\Store as BaseStore;
use DragonCode\Support\Concerns\Makeable;

abstract class Store implements BaseStore
{
    use Call;
    use Makeable;

    public function doesntHave(string $key): bool
    {
        return ! $this->has($key);
    }
}
