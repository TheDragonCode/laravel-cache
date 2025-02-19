<?php

declare(strict_types=1);

namespace DragonCode\Cache\Facades\Support;

use DragonCode\Cache\Support\Tag as Support;
use DragonCode\SimpleDataTransferObject\DataTransferObject;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array get(array|Arrayable|\ArrayObject|DataTransferObject $tags)
 */
class Tag extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Support::class;
    }
}
