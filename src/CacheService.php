<?php

namespace Helldar\Cache;

class CacheService
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $key;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $tags;

    /**
     * @var int
     */
    protected $minutes;

    /**
     * CacheService constructor.
     */
    public function __construct()
    {
        $this->clear();
        $this->minutes(1440);
    }

    /**
     * @param $callback
     *
     * @return mixed
     */
    public function remember($callback)
    {
        if (config('cache.default', 'file') === 'array') {
            return $callback();
        }

        if ($this->tags->isNotEmpty()) {
            return app('cache')
                ->tags($this->tags->toArray())
                ->remember($this->getKey(), $this->minutes, $callback);
        }

        return app('cache')->remember($this->getKey(), $this->minutes, $callback);
    }

    /**
     * Set timeout to cache key.
     *
     * @param int $minutes
     *
     * @return $this
     */
    public function minutes($minutes = 1440)
    {
        $this->minutes = (int) $minutes;

        return $this;
    }

    /**
     * Set tags.
     *
     * @param mixed ...$tags
     *
     * @return $this
     */
    public function tags(...$tags)
    {
        array_map(function ($value) {
            if ($this->tags->has($value)) {
                return true;
            }

            $this->tags->push($value);
        }, $tags);

        return $this;
    }

    /**
     * Set key name.
     *
     * @param mixed ...$keys
     *
     * @return $this
     */
    public function key(...$keys)
    {
        $this->clearKey();

        array_map(function ($key) {
            if (is_array($key)) {
                array_map(function ($key, $value) {
                    $this->pushKeyOrNullable($key);
                    $this->pushKeyOrNullable($value);
                }, array_keys($key), array_values($key));

                return;
            }

            $this->pushKeyOrNullable($key);
        }, $keys);

        return $this;
    }

    private function pushKeyOrNullable($value)
    {
        $this->key->push($value or 'null');
    }

    /**
     * @return string
     */
    private function getKey()
    {
        $key = $this->key->implode('-');

        if (strlen($key) > 100) {
            return str_slug(md5($key));
        }

        return str_slug($key);
    }

    /**
     * Clear cache key value.
     */
    private function clear()
    {
        $this->clearKey();
        $this->clearTags();
    }

    private function clearKey()
    {
        $this->key = collect([]);
    }

    private function clearTags()
    {
        $this->tags = collect([]);
    }
}
