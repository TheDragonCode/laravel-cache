<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

use ArrayObject;
use Closure;
use DragonCode\Contracts\Support\Arrayable as DragonCodeArrayable;
use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Helpers\Instance;
use DragonCode\Support\Helpers\Ables\Arrayable as ArrayableHelper;
use Illuminate\Contracts\Support\Arrayable as IlluminateArrayable;

trait Call
{
    /**
     * @param  mixed  $callback
     *
     * @return mixed
     */
    protected function call($callback = null)
    {
        $callback = $this->resolve($callback);

        return $this->isCallable($callback) ? $callback() : $callback;
    }

    protected function makeCallable($value): callable
    {
        if ($this->isCallable($value)) {
            return $this->resolve($value);
        }

        return function () use ($value) {
            return $this->resolve($value);
        };
    }

    protected function resolve($value)
    {
        return $this->isArrayable($value) ? $this->resolveArray($value) : $value;
    }

    protected function resolveArray($value): array
    {
        if ($this->isArrayable($value)) {
            return Arr::toArray($value);
        }

        return $value;
    }

    protected function isCallable($value): bool
    {
        return is_callable($value);
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
