<?php

declare(strict_types=1);

namespace DragonCode\Cache\Facades\Support;

use DateTimeInterface;
use DragonCode\Cache\Support\Ttl as Support;
use Illuminate\Support\Facades\Facade;

/**
 * @method static int fromDateTime(DateTimeInterface $seconds)
 * @method static int fromMinutes(DateTimeInterface|int $minutes)
 * @method static int fromSeconds(DateTimeInterface|int $seconds)
 */
class Ttl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Support::class;
    }
}
