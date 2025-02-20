<?php

declare(strict_types=1);

namespace DragonCode\Cache\Services;

use Closure;
use DragonCode\Cache\Facades\Support\Key;
use DragonCode\Cache\Facades\Support\Tag;
use DragonCode\Cache\Facades\Support\Ttl;
use DragonCode\Cache\Support\CacheManager;
use DragonCode\Support\Concerns\Makeable;
use DragonCode\Support\Facades\Instances\Call;
use Illuminate\Support\Facades\Auth;

use function array_unshift;
use function config;
use function get_class;
use function is_object;
use function is_string;

/**
 * @method static Cache make()
 */
class Cache
{
    use Makeable;

    protected int $ttl = 86400;

    protected array $tags = [];

    protected mixed $key;

    protected bool $useHash = true;

    protected string $keyHash = '';

    protected bool|object|string $when = true;

    protected array|string|null $auth = null;

    public function when(bool|object|string $when = true): Cache
    {
        $this->when = match (true) {
            is_string($when) => config('cache.enabled.' . $when, true),
            is_object($when) => config('cache.enabled.' . get_class($when), true),
            default          => $when
        };

        return $this;
    }

    public function ttl($value, bool $isMinutes = true): Cache
    {
        $this->ttl = $isMinutes
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

    public function hashKey(bool $hash = true): Cache
    {
        $this->useHash = $hash;

        return $this;
    }

    public function key(...$values): Cache
    {
        $this->key = $values;

        return $this;
    }

    public function get(): mixed
    {
        return $this->manager()->get($this->getKey());
    }

    public function put(mixed $value): mixed
    {
        return $this->manager()->put($this->getKey(), $value, $this->ttl);
    }

    public function remember(mixed $value): mixed
    {
        return $this->manager()->remember($this->getKey(), $value, $this->ttl);
    }

    public function rememberForever(mixed $value): mixed
    {
        return $this->manager()->rememberForever($this->getKey(), $value);
    }

    public function forget(): static
    {
        $this->manager()->forget($this->getKey());

        return $this;
    }

    public function flush(): static
    {
        $this->manager()->flush();

        return $this;
    }

    public function has(): bool
    {
        return $this->manager()->has($this->getKey());
    }

    public function doesntHave(): bool
    {
        return $this->manager()->doesntHave($this->getKey());
    }

    public function call(Closure $callback, mixed $when = true): static
    {
        if (Call::value($when)) {
            Call::value($callback, $this);
        }

        return $this;
    }

    protected function manager(): CacheManager
    {
        return CacheManager::make($this->when)
            ->tags($this->tags);
    }

    protected function getKey(): string
    {
        if (! empty($this->keyHash)) {
            return $this->keyHash;
        }

        $key = $this->key;

        if ($this->auth) {
            array_unshift($key, $this->auth);
        }

        return $this->keyHash = Key::get(':', $key, $this->useHash);
    }
}
