<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Cache\Services\Storages\MainStore;
use DragonCode\Cache\Services\Storages\TaggedStore;
use DragonCode\Contracts\Cache\Store;
use DragonCode\Support\Concerns\Makeable;
use Illuminate\Support\Facades\Cache;

/**
 * @method static CacheManager make()
 */
class CacheManager implements Store
{
    use Makeable;

    protected $tags = [];

    public function tags(array $tags): CacheManager
    {
        $this->tags = $tags;

        return $this;
    }

    public function get(string $key, $default = null)
    {
        return $this->instance()->get($key, $default);
    }

    public function put(string $key, $value, int $seconds)
    {
        return $this->instance()->put($key, $value, $seconds);
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
        return $this->allowTags()
            ? TaggedStore::make()->tags($this->tags)
            : MainStore::make();
    }

    protected function allowTags(): bool
    {
        return ! empty($this->tags) && method_exists(Cache::getStore(), 'tags');
    }
}
