<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services;

use DragonCode\Cache\Facades\Support\CacheManager;
use DragonCode\Cache\Facades\Support\Key;
use DragonCode\Cache\Facades\Support\Tag;
use DragonCode\Support\Concerns\Makeable;

/**
 * @method static Cache make()
 */
class Cache
{
    use Makeable;

    protected $ttl = 86400;

    protected $tags = [];

    protected $key;

    protected $when = true;

    public function when(bool $when = true): Cache
    {
        $this->when = $when;

        return $this;
    }

    public function ttl(int $minutes): Cache
    {
        $this->ttl = $minutes * 60;

        return $this;
    }

    public function tags(string ...$tags): Cache
    {
        $this->tags = Tag::get($tags);

        return $this;
    }

    public function key(...$values): Cache
    {
        $this->key = Key::get('::', $values);

        return $this;
    }

    public function has(): bool
    {
        if ($this->when) {
            return CacheManager::has($this->key);
        }

        return false;
    }

    public function remember(callable $callback)
    {
        if (! $this->when) {
            return $callback();
        }

        return CacheManager::tags($this->tags)->put($this->key, $callback, $this->ttl);
    }
}
