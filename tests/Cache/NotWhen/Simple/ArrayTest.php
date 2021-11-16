<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Simple;

use Tests\Cache\NotWhen\BaseTest;

class ArrayTest extends BaseTest
{
    protected $cache = 'array';

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put($this->value);

        $this->assertNull($this->cache()->get());
    }

    public function testPut()
    {
        $this->assertSame($this->value, $this->cache()->put($this->value));

        $this->assertNull($this->cache()->get());
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put($this->value);

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put($this->value);

        $this->assertFalse($this->cache()->has());
    }
}