<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use DragonCode\Contracts\Cache\Store as BaseStore;
use DragonCode\Support\Concerns\Makeable;

abstract class Store implements BaseStore
{
    use Makeable;
}
