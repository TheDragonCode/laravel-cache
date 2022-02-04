<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

trait Call
{
    use Arrayable;

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
        return $this->isArrayable($value) ? $this->toArray($value) : $value;
    }

    protected function isCallable($value): bool
    {
        return is_callable($value);
    }
}
