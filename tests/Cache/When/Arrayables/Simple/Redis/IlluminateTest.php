<?php

declare(strict_types=1);

namespace Tests\Cache\When\Arrayables\Simple\Redis;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Cache\When\Base;
use Tests\Fixtures\Simple\IlluminateArrayable;

class IlluminateTest extends Base
{
    protected string $cache = 'redis';

    protected mixed $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
    ];

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $item = new IlluminateArrayable();

        $this->cache()->put($item);

        $this->assertSame(serialize($item), serialize($this->cache()->get()));
        $this->assertSame(serialize($item), serialize($this->cache(['qwerty', 'cache'])->get()));

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testPut()
    {
        $item = new IlluminateArrayable();

        $this->assertSame(serialize($item), serialize($this->cache()->put($item)));

        $this->assertSame(serialize($item), serialize($this->cache()->get()));
        $this->assertSame(serialize($item), serialize($this->cache(['qwerty', 'cache'])->get()));

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    #[DataProvider('booleanData')]
    public function testFlexible(bool $isTrue)
    {
        $item = new IlluminateArrayable();

        $interval = $isTrue
            ? $this->positiveTtlInterval
            : $this->negativeTtlInterval;

        $this->assertSame(serialize($item), serialize($this->cache()->flexible($interval)->remember($item)));

        $this->assertSame(serialize($item), serialize($this->cache()->get()));
        $this->assertSame(serialize($item), serialize($this->cache(['qwerty', 'cache'])->get()));

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testRemember()
    {
        $item = new IlluminateArrayable();

        $this->assertSame(serialize($item), serialize($this->cache()->remember($item)));

        $this->assertSame(serialize($item), serialize($this->cache()->get()));
        $this->assertSame(serialize($item), serialize($this->cache(['qwerty', 'cache'])->get()));

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testRememberForever()
    {
        $item = new IlluminateArrayable();

        $this->assertSame(serialize($item), serialize($this->cache()->rememberForever($item)));

        $this->assertSame(serialize($item), serialize($this->cache()->get()));
        $this->assertSame(serialize($item), serialize($this->cache(['qwerty', 'cache'])->get()));

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(new IlluminateArrayable());

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
        $this->assertNull($this->cache(['qwerty', 'cache'])->get());
        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put(new IlluminateArrayable());

        $this->assertTrue($this->cache()->has());
        $this->assertTrue($this->cache(['qwerty', 'cache'])->has());

        $this->assertFalse($this->cache(['qwerty'])->has());
        $this->assertFalse($this->cache(['cache'])->has());
    }

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put(new IlluminateArrayable());

        $this->assertFalse($this->cache()->doesntHave());
        $this->assertFalse($this->cache(['qwerty', 'cache'])->doesntHave());

        $this->assertTrue($this->cache(['qwerty'])->doesntHave());
        $this->assertTrue($this->cache(['cache'])->doesntHave());
    }
}
