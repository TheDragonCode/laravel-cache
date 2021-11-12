<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use Illuminate\Support\Facades\Cache;

class WithoutTags extends Store
{
    public function put(string $key, $value, int $seconds)
    {
        return Cache::put($key, $value, $seconds);
    }
}
