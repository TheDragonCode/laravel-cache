<?php

declare(strict_types=1);

namespace Tests\Cache\When;

class FileTest extends BaseTest
{
    protected $cache = 'file';

    public function testRemember()
    {
        $this->assertSame($this->value, $this->cache()->remember(function () {
            return $this->value;
        }));

        $this->assertTrue($this->cache()->has());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->remember(function () {
            return $this->value;
        });

        $this->assertTrue($this->cache()->has());
    }
}
