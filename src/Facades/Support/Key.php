<?php

declare(strict_types=1);

namespace DragonCode\Cache\Facades\Support;

use DragonCode\Cache\Support\Key as Support;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string get(string $separator, array $values)
 */
class Key extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Support::class;
    }
}
