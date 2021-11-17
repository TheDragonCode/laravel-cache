<?php

declare(strict_types=1);

namespace Tests\Cache\When\Dto;

use Tests\Cache\When\BaseTest;
use Tests\Fixtures\Concerns\Dtoable;

class ArrayTest extends BaseTest
{
    use Dtoable;

    protected $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
    ];

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put($this->dto());

        $this->assertSame($this->value, $this->cache()->get());
    }

    public function testPut()
    {
        $this->assertSame($this->value, $this->cache()->put($this->dto()));

        $this->assertSame($this->value, $this->cache()->get());
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put($this->dto());

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put($this->dto());

        $this->assertTrue($this->cache()->has());
    }
}
