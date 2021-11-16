<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

trait Call
{
    /**
     * @param  mixed  $callback
     *
     * @return mixed
     */
    protected function call($callback = null)
    {
        return $this->isCallable($callback) ? $callback() : $callback;
    }

    protected function makeCallable($value): callable
    {
        if ($this->isCallable($value)) {
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
}
