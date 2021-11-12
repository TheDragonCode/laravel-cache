<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use Illuminate\Support\Facades\Cache;

class WithTags extends Store
{
    protected $tags = [];

    public function tags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function put(string $key, callable $callback, int $seconds)
    {
        return Cache::tags($this->tags)->remember($key, $seconds, $callback);
    }
}
