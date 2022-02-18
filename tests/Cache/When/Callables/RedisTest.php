<?php

declare(strict_types=1);

namespace Tests\Cache\When\Callables;

use Tests\Cache\When\BaseTest;

class RedisTest extends BaseTest
{
    protected $cache = 'redis';

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $item = function () {
            return $this->value;
        };

        $this->assertSame($this->value, $this->cache()->put($item));

        $this->assertSame($this->value, $this->cache()->get());
        $this->assertSame($this->value, $this->cache(['qwerty', 'cache'])->get());

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testPut()
    {
        $item = function () {
            return $this->value;
        };

        $this->assertSame($this->value, $this->cache()->put($item));

        $this->assertSame($this->value, $this->cache()->get());
        $this->assertSame($this->value, $this->cache(['qwerty', 'cache'])->get());

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testRemember()
    {
        $item = function () {
            return $this->value;
        };

        $this->assertSame($this->value, $this->cache()->remember($item));

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

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->assertFalse($this->cache()->doesntHave());
        $this->assertFalse($this->cache(['qwerty', 'cache'])->doesntHave());

        $this->assertTrue($this->cache(['qwerty'])->doesntHave());
        $this->assertTrue($this->cache(['cache'])->doesntHave());
    }

    public function testCallable()
    {
        $user = $this->createUser();

        $this->assertSame($user, $this->cache()->put($user));

        $this->assertTrue($this->cache()->has());

        $this->assertSame(serialize($user), serialize($this->cache()->get()));
    }
}
