<?php

declare(strict_types=1);

namespace Tests\Cache\When\Callables;

use Tests\Cache\When\Base;

class FileTest extends Base
{
    protected string $cache = 'file';

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $item = function () {
            return $this->value;
        };

        $this->assertSame($this->value, $this->cache()->put($item));
        $this->assertSame($this->value, $this->cache()->get());
    }

    public function testPut()
    {
        $item = function () {
            return $this->value;
        };

        $this->assertSame($this->value, $this->cache()->put($item));
        $this->assertSame($this->value, $this->cache()->get());
    }

    public function testRemember()
    {
        $item = function () {
            return $this->value;
        };

        $this->assertSame($this->value, $this->cache()->remember($item));
        $this->assertSame($this->value, $this->cache()->get());
    }

    public function testRememberForever()
    {
        $item = function () {
            return $this->value;
        };

        $this->assertSame($this->value, $this->cache()->rememberForever($item));
        $this->assertSame($this->value, $this->cache()->get());
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $item = function () {
            return $this->value;
        };

        $this->assertSame($this->value, $this->cache()->put($item));

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $item = function () {
            return $this->value;
        };

        $this->assertSame($this->value, $this->cache()->put($item));

        $this->assertTrue($this->cache()->has());
    }

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $item = function () {
            return $this->value;
        };

        $this->assertSame($this->value, $this->cache()->put($item));

        $this->assertFalse($this->cache()->doesntHave());
    }

    public function testCallable()
    {
        $user = $this->createUser();

        $this->assertSame($user, $this->cache()->put($user));

        $this->assertTrue($this->cache()->has());

        $this->assertSame(serialize($user), serialize($this->cache()->get()));
    }
}
