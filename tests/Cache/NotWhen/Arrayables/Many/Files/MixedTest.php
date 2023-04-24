<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Arrayables\Many\Files;

use Tests\Cache\NotWhen\Base;
use Tests\Fixtures\Many\MixedArrayable;

class MixedTest extends Base
{
    protected $cache = 'file';

    protected $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
        'baz' => [
            'foo' => 'Foo',
            'bar' => 'Bar',
        ],
        'baq' => [
            'foo' => 'Foo',
            'bar' => 'Bar',
        ],
    ];

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(new MixedArrayable());

        $this->assertNull($this->cache()->get());
    }

    public function testPut()
    {
        $item = new MixedArrayable();

        $this->assertSame($item, $this->cache()->put($item));

        $this->assertNull($this->cache()->get());
    }

    public function testRemember()
    {
        $item = new MixedArrayable();

        $this->assertSame($item, $this->cache()->remember($item));

        $this->assertNull($this->cache()->get());
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(new MixedArrayable());

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put(new MixedArrayable());

        $this->assertFalse($this->cache()->has());
    }

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put(new MixedArrayable());

        $this->assertTrue($this->cache()->doesntHave());
    }
}
