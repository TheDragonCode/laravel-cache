<?php

declare(strict_types=1);

namespace Tests\Cache\When;

class RedisTest extends BaseTest
{
    protected $cache = 'redis';

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->assertSame($this->value, $this->cache()->get());
        $this->assertSame($this->value, $this->cache(['qwerty', 'cache'])->get());

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testPut()
    {
        $this->assertSame($this->value, $this->cache()->put(function () {
            return $this->value;
        }));

        $this->assertSame($this->value, $this->cache()->get());
        $this->assertSame($this->value, $this->cache(['qwerty', 'cache'])->get());

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
        $this->assertNull($this->cache(['qwerty', 'cache'])->get());

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->assertTrue($this->cache()->has());
        $this->assertTrue($this->cache(['qwerty', 'cache'])->has());

        $this->assertFalse($this->cache(['qwerty'])->has());
        $this->assertFalse($this->cache(['cache'])->has());
    }
}
