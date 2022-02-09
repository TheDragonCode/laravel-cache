<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

use ArrayAccess;
use ArrayObject;
use Closure;
use DragonCode\Contracts\Support\Arrayable as DragonCodeArrayable;
use DragonCode\Support\Facades\Helpers\Ables\Arrayable as Helper;
use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Helpers\Instance;
use DragonCode\Support\Facades\Helpers\Reflection;
use DragonCode\Support\Helpers\Ables\Arrayable as ArrayableHelper;
use Illuminate\Contracts\Support\Arrayable as IlluminateArrayable;

trait Arrayable
{
    protected function arrayMap(array $values, callable $callback): array
    {
        return Helper::of($values)
            ->map(function ($value) {
                if ($this->isArrayable($value)) {
                    return Arr::toArray($value);
                }

                if (is_object($value)) {
                    return get_class($value);
                }

                return $value;
            })
            ->flatten()
            ->filter(static function ($value) {
                return ! empty($value) || is_numeric($value);
            })
            ->map($callback)
            ->values()
            ->get();
    }

    protected function toArray($value): array
    {
        return Arr::toArray($value);
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

        return (bool) (Instance::of($value, Closure::class) && method_exists($value, 'toArray'));
    }
}
