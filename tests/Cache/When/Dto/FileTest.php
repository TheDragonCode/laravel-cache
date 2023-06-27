<?php

declare(strict_types=1);

namespace Tests\Cache\When\Dto;

use Tests\Cache\When\Base;
use Tests\Fixtures\Concerns\Dtoable;

class FileTest extends Base
{
    use Dtoable;

    protected string $cache = 'file';

    protected mixed $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
    ];

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $item = $this->dto();

        $this->cache()->put($item);

        $this->assertSame(serialize($item), serialize($this->cache()->get()));
    }

    public function testPut()
    {
        $item = $this->dto();

        $this->assertSame(serialize($item), serialize($this->cache()->put($item)));
        $this->assertSame(serialize($item), serialize($this->cache()->get()));
    }

    public function testRemember()
    {
        $item = $this->dto();

        $this->assertSame(serialize($item), serialize($this->cache()->remember($item)));
        $this->assertSame(serialize($item), serialize($this->cache()->get()));
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

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->dto());

        $this->assertFalse($this->cache()->doesntHave());
    }
}
