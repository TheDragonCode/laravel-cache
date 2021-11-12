<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use Illuminate\Support\Facades\Cache;

class WithoutTags extends Store
{
    public function put(string $key, callable $callback, int $seconds)
    {
        return Cache::remember($key, $seconds, $callback);
    }
}
