<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Dto;

use Tests\Cache\NotWhen\Base;
use Tests\Fixtures\Concerns\Dtoable;

class ArrayTest extends Base
{
    use Dtoable;

    protected mixed $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
    ];

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put($this->dto());

        $this->assertNull($this->cache()->get());
    }

    public function testPut()
    {
        $item = $this->dto();

        $this->assertSame($item, $this->cache()->put($item));

        $this->assertNull($this->cache()->get());
    }

    public function testRemember()
    {
        $item = $this->dto();

        $this->assertSame($item, $this->cache()->remember($item));

        $this->assertNull($this->cache()->get());
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

        $this->assertFalse($this->cache()->has());
    }

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->dto());

        $this->assertTrue($this->cache()->doesntHave());
    }
}
