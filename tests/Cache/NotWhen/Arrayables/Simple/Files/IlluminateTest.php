<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Arrayables\Simple\Files;

use Tests\Cache\NotWhen\Base;
use Tests\Fixtures\Simple\IlluminateArrayable;

class IlluminateTest extends Base
{
    protected $cache = 'file';

    protected $value = [
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

    public function testRemember()
    {
        $item = new IlluminateArrayable();

        $this->assertSame($item, $this->cache()->remember($item));

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
