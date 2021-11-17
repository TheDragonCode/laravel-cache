<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Arrayables\Many\Files;

use Tests\Cache\NotWhen\BaseTest;
use Tests\Fixtures\Many\IlluminateArrayable;

class IlluminateTest extends BaseTest
{
    protected $cache = 'file';

    protected $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
        'baz' => [
            'foo' => 'Foo',
            'bar' => 'Bar',
        ],
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
