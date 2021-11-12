<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen;

class RedisTest extends BaseTest
{
    protected $cache = 'redis';

    public function testRemember()
    {
        $this->assertSame($this->value, $this->cache()->remember(function () {
            return $this->value;
        }));

        $this->assertFalse($this->cache()->has());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->remember(function () {
            return $this->value;
        });

        $this->assertFalse($this->cache()->has());
        $this->assertFalse($this->cache(['pretty'])->has());
        $this->assertFalse($this->cache(['cache'])->has());
        $this->assertFalse($this->cache(['qwerty'])->has());
    }
}
