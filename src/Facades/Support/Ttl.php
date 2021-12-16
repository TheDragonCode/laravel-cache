<?php

declare(strict_types=1);

namespace DragonCode\Cache\Facades\Support;

use DateTimeInterface;
use DragonCode\Cache\Support\Ttl as Support;
use Illuminate\Support\Facades\Facade;

/**
 * @method static int fromDateTime(mixed|DateTimeInterface $seconds)
 * @method static int fromMinutes(mixed|DateTimeInterface|string|int|callable $minutes)
 * @method static int fromSeconds(mixed|DateTimeInterface|string|int|callable $seconds)
 */
class Ttl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Support::class;
    }
}
