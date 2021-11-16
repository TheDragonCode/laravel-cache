<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

use DragonCode\Support\Facades\Helpers\Ables\Arrayable as Helper;

trait Arrayable
{
    protected function arrayMap(array $values, callable $callback): array
    {
        return Helper::of($values)
            ->flatten()
            ->map($callback)
            ->get();
    }
}
