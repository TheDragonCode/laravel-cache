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
        return is_callable($callback) ? $callback() : $callback;
    }

    protected function makeCallable($value): callable
    {
        if (! is_callable($value)) {
            return function () use ($value) {
                return $value;
            };
        }

        return $value;
    }
}
