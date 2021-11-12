<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use Illuminate\Cache\TaggedCache;
use Illuminate\Support\Facades\Cache;

class WithTags extends Store
{
    protected $tags = [];

    public function tags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function get(string $key, callable $default = null)
    {
        if ($this->has($key)) {
            return $this->cache()->get($key);
        }

        return $default();
    }

    public function put(string $key, callable $callback, int $seconds)
    {
        return $this->cache()->remember($key, $seconds, $callback);
    }

    public function forget(string $key): void
    {
        $this->cache()->forget($key);
    }

    public function has(string $key): bool
    {
        return $this->cache()->has($key);
    }

    protected function cache(): TaggedCache
    {
        return Cache::tags($this->tags);
    }
}