<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Arrayables\Simple\Files;

use Tests\Cache\NotWhen\Base;
use Tests\Fixtures\Simple\DragonCodeArrayable;

class DragonCodeTest extends Base
{
    protected string $cache = 'file';

    protected mixed $value = [
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
        $item = new DragonCodeArrayable();

        $this->assertSame($item, $this->cache()->put($item));

        $this->assertNull($this->cache()->get());
    }

    public function testRemember()
    {
        $item = new DragonCodeArrayable();

        $this->assertSame($item, $this->cache()->remember($item));

        $this->assertNull($this->cache()->get());
    }

    public function testRememberForever()
    {
        $item = new DragonCodeArrayable();

        $this->assertSame($item, $this->cache()->rememberForever($item));

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
