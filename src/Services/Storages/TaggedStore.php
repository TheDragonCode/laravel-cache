<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services\Storages;

use Illuminate\Cache\TaggedCache;
use Illuminate\Support\Facades\Cache;

class TaggedStore extends Store
{
    protected array $tags = [];

    public function tags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function get(string $key, $default = null): mixed
    {
        if ($this->has($key)) {
            return $this->cache()->get($key);
        }

        return $this->call($default);
    }

    public function put(string $key, $value, int $seconds): mixed
    {
        $value = $this->call($value);

        $this->cache()->put($key, $value, $seconds);

        return $value;
    }

    public function remember(string $key, $value, int $seconds): mixed
    {
        $value = $this->makeCallable($value);

        return $this->cache()->remember($key, $seconds, $value);
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
