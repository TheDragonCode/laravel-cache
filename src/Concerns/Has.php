<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

use Carbon\Carbon;
use Closure;
use DateTimeInterface;
use DragonCode\Contracts\Cache\Ttl as TtlContract;
use DragonCode\Support\Facades\Helpers\Instance;
use DragonCode\Support\Facades\Helpers\Is;

trait Has
{
    protected function hasDateTime($value): bool
    {
        return Instance::of($value, [Carbon::class, DateTimeInterface::class]);
    }

    protected function hasClosure($value): bool
    {
        return Instance::of($value, Closure::class);
    }

    protected function hasObject($value): bool
    {
        return Is::object($value);
    }

    protected function hasContract($value): bool
    {
        return Instance::of($value, TtlContract::class);
    }
}
