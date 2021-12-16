<?php

declare(strict_types=1);

namespace DragonCode\Cache\Facades\Support;

use DragonCode\Cache\Support\TtlBy as Support;
use Illuminate\Support\Facades\Facade;

/**
 * @method static int get(object|string $value)
 */
class TtlBy extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Support::class;
    }
}
