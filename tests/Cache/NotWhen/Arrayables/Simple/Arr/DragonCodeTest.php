<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Arrayables\Simple\Arr;

use Tests\Cache\NotWhen\BaseTest;
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

        $this->cache()->put(new DragonCodeArrayable());

        $this->assertNull($this->cache()->get());
    }

    public function testPut()
    {
        $this->assertSame($this->value, $this->cache()->put(new DragonCodeArrayable()));

        $this->assertNull($this->cache()->get());
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

        $this->assertFalse($this->cache()->has());
    }

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put(new DragonCodeArrayable());

        $this->assertTrue($this->cache()->doesntHave());
    }
}
