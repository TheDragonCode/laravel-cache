<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Cache\Services\Storages\Disabled;
use DragonCode\Cache\Services\Storages\MainStore;
use DragonCode\Cache\Services\Storages\Store;
use DragonCode\Cache\Services\Storages\TaggedStore;
use DragonCode\Support\Concerns\Makeable;
use Illuminate\Support\Facades\Cache;

use function method_exists;

/**
 * @method static CacheManager make(bool $when = true)
 */
class CacheManager extends Store
{
    use Makeable;

    protected array $tags = [];

    public function __construct(
        protected bool $when = true
    ) {}

    public function tags(array $tags): CacheManager
    {
        $this->tags = $tags;

        return $this;
    }

    public function get(string $key, $default = null): mixed
    {
        return $this->instance()->get($key, $default);
    }

    public function put(string $key, $value, int $seconds): mixed
    {
        return $this->instance()->put($key, $value, $seconds);
    }

    public function flexible(string $key, $value, int $seconds, int $interval): mixed
    {
        if ($interval < 0) {
            $interval = $seconds - $interval;
        }
        elseif ($interval === 0) {
            $interval = (int) (300 * 0.85);
        }

        return $this->instance()->flexible($key, $value, $seconds, $interval);
    }

    public function remember(string $key, $value, int $seconds): mixed
    {
        return $this->instance()->remember($key, $value, $seconds);
    }

    public function rememberForever(string $key, $value): mixed
    {
        return $this->instance()->rememberForever($key, $value);
    }

    public function forget(string $key): void
    {
        $this->instance()->forget($key);
    }

    public function has(string $key): bool
    {
        return $this->instance()->has($key);
    }

    public function flush(): void
    {
        $this->instance()->flush();
    }

    protected function instance(): Store
    {
        return match (true) {
            $this->isDisabled() => Disabled::make(),
            $this->allowTags()  => TaggedStore::make()->tags($this->tags),
            default             => MainStore::make(),
        };
    }

    protected function isDisabled(): bool
    {
        return ! $this->when;
    }

    protected function allowTags(): bool
    {
        return ! empty($this->tags) && method_exists(Cache::getStore(), 'tags');
    }
}
