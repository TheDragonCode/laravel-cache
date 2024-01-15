<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

trait Call
{
    use Arrayable;

    protected function call(mixed $callback = null): mixed
    {
        return $this->isCallable($callback) && ! $this->isFunction($callback) ? $callback() : $callback;
    }

    protected function makeCallable($value): callable
    {
        if ($this->isCallable($value) && ! $this->isFunction($value)) {
            return $value;
        }

        return function () use ($value) {
            return $value;
        };
    }

    protected function isCallable($value): bool
    {
        return is_callable($value);
    }

    protected function isFunction($value): bool
    {
        return is_string($value) && function_exists($value);
    }
}
