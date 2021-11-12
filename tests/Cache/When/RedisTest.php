<?php

declare(strict_types=1);

namespace Tests\Cache\When;

class RedisTest extends BaseTest
{
    protected $cache = 'redis';

    public function testRemember()
    {
        $this->assertSame($this->value, $this->cache()->remember(function () {
            return $this->value;
        }));

        $this->assertTrue($this->cache()->has());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->remember(function () {
            return $this->value;
        });

        $this->assertTrue($this->cache()->has());
        $this->assertTrue($this->cache(['pretty', 'cache'])->has());
        $this->assertTrue($this->cache(['pretty'])->has());
        $this->assertTrue($this->cache(['cache'])->has());

        $this->assertFalse($this->cache(['qwerty'])->has());
    }
}
