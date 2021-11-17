<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Arrayables\Simple\Arr;

use Tests\Cache\NotWhen\BaseTest;
use Tests\Fixtures\Simple\IlluminateArrayable;

class IlluminateTest extends BaseTest
{
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
        $this->assertSame($this->value, $this->cache()->put(new IlluminateArrayable()));

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
}
