<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Cache\Services\Storages\Arr;
use DragonCode\Cache\Services\Storages\WithoutTags;
use DragonCode\Cache\Services\Storages\WithTags;
use DragonCode\Contracts\Cache\Store;
use Illuminate\Support\Facades\Cache;

class CacheManager implements Store
{
    protected $tags = [];

    public function tags(array $tags): CacheManager
    {
        $this->tags = $tags;

        return $this;
    }

    public function get(string $key, callable $default = null)
    {
        return $this->instance()->get($key, $default);
    }

    public function put(string $key, callable $callback, int $seconds)
    {
        return $this->instance()->put($key, $callback, $seconds);
    }

    public function forget(string $key): void
    {
        $this->instance()->forget($key);
    }

    public function has(string $key): bool
    {
        return $this->instance()->has($key);
    }

    protected function instance(): Store
    {
        switch (true) {
            case $this->isArray():
                return Arr::make();

            case $this->allowTags():
                return WithTags::make()->tags($this->tags);

            default:
                return WithoutTags::make();
        }
    }

    protected function isArray(): bool
    {
        return config('cache.default', 'file') === 'array';
    }

    protected function allowTags(): bool
    {
        return ! empty($this->tags) && Cache::supportsTags();
    }
}
