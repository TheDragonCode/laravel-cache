<?php

declare(strict_types=1);

namespace DragonCode\Cache\Facades\Support;

use ArrayObject;
use DragonCode\Cache\Support\Key as Support;
use DragonCode\Contracts\DataTransferObject\DataTransferObject;
use DragonCode\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string get(string $separator, array|Arrayable|\Illuminate\Contracts\Support\Arrayable|ArrayObject|DataTransferObject $values, bool $hash = true)
 */
class Key extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Support::class;
    }
}
