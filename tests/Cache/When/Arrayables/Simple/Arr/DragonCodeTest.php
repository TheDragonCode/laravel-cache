<?php

declare(strict_types=1);

namespace Tests\Cache\When\Arrayables\Simple\Arr;

use Tests\Cache\When\BaseTest;
use Tests\Fixtures\Simple\DragonCodeArrayable;

class DragonCodeTest extends BaseTest
{
    protected $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
    ];

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $item = new DragonCodeArrayable();

        $this->cache()->put($item);

        $this->assertSame(serialize($item), serialize($this->cache()->get()));
    }

    public function testPut()
    {
        $item = new DragonCodeArrayable();

        $this->assertSame(serialize($item), serialize($this->cache()->put($item)));
        $this->assertSame(serialize($item), serialize($this->cache()->get()));
    }

    public function testRemember()
    {
        $item = new DragonCodeArrayable();

        $this->assertSame(serialize($item), serialize($this->cache()->remember($item)));
        $this->assertSame(serialize($item), serialize($this->cache()->get()));
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(new DragonCodeArrayable());

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put(new DragonCodeArrayable());

        $this->assertTrue($this->cache()->has());
    }

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put(new DragonCodeArrayable());

        $this->assertFalse($this->cache()->doesntHave());
    }
}
