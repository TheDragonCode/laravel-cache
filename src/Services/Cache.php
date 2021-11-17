<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services;

use DragonCode\Cache\Concerns\Call;
use DragonCode\Cache\Facades\Support\Key;
use DragonCode\Cache\Facades\Support\Tag;
use DragonCode\Cache\Support\CacheManager;
use DragonCode\Support\Concerns\Makeable;

/**
 * @method static Cache make()
 */
class Cache
{
    use Call;
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
        $this->key = Key::get(':', $values);

        return $this;
    }

    public function get()
    {
        return $this->manager()->get($this->key);
    }

    /**
     * @param  mixed  $value
     *
     * @return mixed
     */
    public function put($value)
    {
        return $this->manager()->put($this->key, $value, $this->ttl);
    }

    public function forget(): void
    {
        $this->manager()->forget($this->key);
    }

    public function has(): bool
    {
        return $this->manager()->has($this->key);
    }

    protected function manager(): CacheManager
    {
        return CacheManager::make($this->when)
            ->tags($this->tags);
    }
}
