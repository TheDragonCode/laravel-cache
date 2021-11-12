<?php

declare(strict_types=1);

namespace DragonCode\Cache\Facades\Support;

use DragonCode\Cache\Support\CacheManager as Support;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key)
 * @method static mixed put(string $key, mixed $value, int $seconds)
 * @method static Support tags(array $tags)
 * @method static void forget(string $key)
 */
class CacheManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Support::class;
    }
}
