<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Dto;

use Tests\Cache\NotWhen\BaseTest;
use Tests\Fixtures\Concerns\Dtoable;

class FileTest extends BaseTest
{
    use Dtoable;

    protected $cache = 'file';

    protected $value = [
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
        $this->assertSame($this->value, $this->cache()->put($this->dto()));

        $this->assertNull($this->cache()->get());
    }

    public function testRemember()
    {
        $this->assertSame($this->value, $this->cache()->remember($this->dto()));

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
