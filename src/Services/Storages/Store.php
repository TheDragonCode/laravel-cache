<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use DragonCode\Cache\Concerns\Call;
use DragonCode\Support\Concerns\Makeable;

abstract class Store
{
    use Call;
    use Makeable;

    public function doesntHave(string $key): bool
    {
        return ! $this->has($key);
    }
}
