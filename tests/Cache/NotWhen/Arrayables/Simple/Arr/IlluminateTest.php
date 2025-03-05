<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Arrayables\Simple\Arr;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Cache\NotWhen\Base;
use Tests\Fixtures\Simple\DragonCodeArrayable;
use Tests\Fixtures\Simple\IlluminateArrayable;

class IlluminateTest extends Base
{
    protected mixed $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
    ];

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(new IlluminateArrayable());

        $this->assertNull($this->cache()->get());
    }

    public function testPut()
    {
        $item = new IlluminateArrayable();

        $this->assertSame($item, $this->cache()->put($item));

        $this->assertNull($this->cache()->get());
    }

    #[DataProvider('booleanData')]
    public function testFlexible(bool $isTrue)
    {
        $item = new IlluminateArrayable();

        $interval = $isTrue
            ? $this->positiveTtlInterval
            : $this->negativeTtlInterval;

        $this->assertSame($item, $this->cache()->flexible($interval)->remember($item));

        $this->assertNull($this->cache()->flexible($interval)->get());
    }

    public function testRemember()
    {
        $item = new IlluminateArrayable();

        $this->assertSame($item, $this->cache()->remember($item));

        $this->assertNull($this->cache()->get());
    }

    public function testRememberForever()
    {
        $item = new IlluminateArrayable();

        $this->assertSame($item, $this->cache()->rememberForever($item));

        $this->assertNull($this->cache()->get());
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(new IlluminateArrayable());

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put(new IlluminateArrayable());

        $this->assertFalse($this->cache()->has());
    }

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put(new IlluminateArrayable());

        $this->assertTrue($this->cache()->doesntHave());
    }
}
