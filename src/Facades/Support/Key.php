<?php

declare(strict_types=1);

namespace DragonCode\Cache\Facades\Support;

use ArrayObject;
use DragonCode\Cache\Support\Key as Support;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string get(string $separator, array|Arrayable|ArrayObject $values, bool $hash = true)
 */
class Key extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Support::class;
    }
}
