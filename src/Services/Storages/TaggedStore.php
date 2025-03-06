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
        return $this->cache()->get($key)
            ?? $this->call($default);
    }

    public function put(string $key, $value, int $seconds): mixed
    {
        $value = $this->call($value);

        $this->cache()->put($key, $value, $seconds);

        return $value;
    }

    public function flexible(string $key, $value, int $seconds, int $interval): mixed
    {
        return $this->cache()->flexible($key, [$interval, $seconds], $this->makeCallable($value));
    }

    public function remember(string $key, $value, int $seconds): mixed
    {
        return $this->cache()->remember($key, $seconds, $this->makeCallable($value));
    }

    public function rememberForever(string $key, $value): mixed
    {
        return $this->cache()->rememberForever($key, $this->makeCallable($value));
    }

    public function forget(string $key): void
    {
        $this->cache()->forget($key);
    }

    public function has(string $key): bool
    {
        return $this->cache()->has($key);
    }

    public function flush(): void
    {
        $this->cache()->flush();
    }

    protected function cache(): TaggedCache
    {
        return Cache::tags($this->tags);
    }
}
