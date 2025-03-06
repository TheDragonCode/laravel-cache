<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

use function function_exists;
use function is_callable;
use function is_string;

trait Call
{
    use Arrayable;

    protected function call(mixed $callback = null): mixed
    {
        return is_callable($callback) && ! $this->isFunction($callback) ? $callback() : $callback;
    }

    protected function makeCallable(mixed $value): callable
    {
        if (is_callable($value) && ! $this->isFunction($value)) {
            return $value;
        }

        return static fn () => $value;
    }

    protected function isFunction($value): bool
    {
        return is_string($value) && function_exists($value);
    }
}
