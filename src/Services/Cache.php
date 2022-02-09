<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services;

use DragonCode\Cache\Facades\Support\Key;
use DragonCode\Cache\Facades\Support\Tag;
use DragonCode\Cache\Facades\Support\Ttl;
use DragonCode\Cache\Support\CacheManager;
use DragonCode\Support\Concerns\Makeable;
use Illuminate\Support\Facades\Auth;

/**
 * @method static Cache make()
 */
class Cache
{
    use Makeable;

    protected $ttl = 86400;

    protected $tags = [];

    protected $key;

    protected $key_hash;

    protected $when = true;

    protected $auth;

    public function when(bool $when = true): Cache
    {
        $this->when = $when;

        return $this;
    }

    public function ttl($value, bool $is_minutes = true): Cache
    {
        $this->ttl = $is_minutes
            ? Ttl::fromMinutes($value)
            : Ttl::fromSeconds($value);

        return $this;
    }

    public function tags(string ...$tags): Cache
    {
        $this->tags = Tag::get($tags);

        return $this;
    }

    public function withAuth(): Cache
    {
        $this->auth = Auth::check() ? [get_class(Auth::user()), Auth::id()] : 'guest';

        return $this;
    }

    public function key(...$values): Cache
    {
        $this->key = $values;

        return $this;
    }

    public function get()
    {
        return $this->manager()->get($this->getKey());
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function put($value)
    {
        return $this->manager()->put($this->getKey(), $value, $this->ttl);
    }

    public function remember($value)
    {
        return $this->put($value);
    }

    public function forget(): void
    {
        $this->manager()->forget($this->getKey());
    }

    public function has(): bool
    {
        return $this->manager()->has($this->getKey());
    }

    public function doesntHave(): bool
    {
        return $this->manager()->doesntHave($this->getKey());
    }

    protected function manager(): CacheManager
    {
        return CacheManager::make($this->when)
            ->tags($this->tags);
    }

    protected function getKey(): string
    {
        if (! empty($this->key_hash)) {
            return $this->key_hash;
        }

        $key = $this->key;

        if ($this->auth) {
            array_unshift($key, $this->auth);
        }

        return $this->key_hash = Key::get(':', $key);
    }
}
