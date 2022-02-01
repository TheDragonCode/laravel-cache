<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

use ArrayObject;
use Closure;
use DragonCode\Contracts\Support\Arrayable as DragonCodeArrayable;
use DragonCode\Support\Facades\Helpers\Ables\Arrayable as Helper;
use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Helpers\Instance;
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
        $value = $this->resolveArray($value);

        return Arr::wrap($value);
    }

    protected function resolveArray($value): array
    {
        if ($this->isArrayable($value)) {
            return Arr::toArray($value);
        }

        return $value;
    }

    protected function isArrayable($value): bool
    {
        if (Instance::of($value, [DragonCodeArrayable::class, IlluminateArrayable::class, ArrayableHelper::class, ArrayObject::class])) {
            return true;
        }

        if (Instance::of($value, Closure::class) && method_exists($value, 'toArray')) {
            return true;
        }

        return false;
    }
}
