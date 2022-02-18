<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Callables;

use Tests\Cache\NotWhen\BaseTest;
use Tests\Fixtures\Models\User;

class FileTest extends BaseTest
{
    protected $cache = 'file';

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->assertNull($this->cache()->get());
    }

    public function testPut()
    {
        $item = function () {
            return $this->value;
        };

        $this->assertSame($item, $this->cache()->put($item));

        $this->assertNull($this->cache()->get());
    }

    public function testRemember()
    {
        $item = function () {
            return $this->value;
        };

        $this->assertSame($item, $this->cache()->remember($item));

        $this->assertNull($this->cache()->get());
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->assertFalse($this->cache()->has());
    }

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->assertTrue($this->cache()->doesntHave());
    }

    public function testCallable()
    {
        $user = new User([
            'id'   => 123,
            'name' => 'John Doe',
        ]);

        $this->cache()->put($user);

        $this->assertTrue($this->cache()->doesntHave());
    }
}
