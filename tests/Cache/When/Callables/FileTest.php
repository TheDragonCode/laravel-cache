<?php

declare(strict_types=1);

namespace Tests\Cache\When\Callables;

use Tests\Cache\When\BaseTest;

class FileTest extends BaseTest
{
    protected $cache = 'file';

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->assertSame($this->value, $this->cache()->get());
    }

    public function testPut()
    {
        $this->assertSame($this->value, $this->cache()->put(function () {
            return $this->value;
        }));

        $this->assertSame($this->value, $this->cache()->get());
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put(function () {
            return $this->value;
        });

        $this->assertTrue($this->cache()->has());
    }
}
