<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

use ArrayAccess;
use ArrayObject;
use Carbon\Carbon;
use Closure;
use DragonCode\Contracts\Support\Arrayable as DragonCodeArrayable;
use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Instances\Instance;
use DragonCode\Support\Facades\Instances\Reflection;
use DragonCode\Support\Helpers\Ables\Arrayable as ArrayableHelper;
use Illuminate\Contracts\Support\Arrayable as IlluminateArrayable;

trait Arrayable
{
    protected function arrayMap(array $values, callable $callback): array
    {
        return Arr::of($values)
            ->map(function ($value) {
                if ($this->isArrayable($value)) {
                    return $this->toArray($value);
                }

                if (is_object($value)) {
                    return get_class($value);
                }

                return $value;
            })
            ->flatten()
            ->filter(static fn ($value) => ! empty($value) || is_numeric($value))
            ->map($callback)
            ->values()
            ->toArray();
    }

    protected function toArray($value): array
    {
        return Arr::of(Arr::wrap($value))
            ->map(fn ($value) => Instance::of($value, Carbon::class) ? $value->toIso8601String() : $value)
            ->resolve()
            ->toArray();
    }

    protected function isArrayable($value): bool
    {
        if (is_array($value)) {
            return true;
        }

        if (
            is_string($value)
            && method_exists($value, 'toArray')
            && ! Reflection::isStaticMethod($value, 'toArray')
        ) {
            return false;
        }

        if (
            Instance::of($value, [
                DragonCodeArrayable::class,
                IlluminateArrayable::class,
                ArrayableHelper::class,
                ArrayObject::class,
                ArrayAccess::class,
                DragonCodeArrayable::class,
            ])
        ) {
            return true;
        }

        return Instance::of($value, Closure::class) && method_exists($value, 'toArray');
    }
}
