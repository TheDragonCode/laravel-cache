<?php

declare(strict_types=1);

namespace Tests\Cache\When\Simple;

use DragonCode\Cache\Services\Cache;
use Tests\Cache\When\Base;

class FileTest extends Base
{
    protected string $cache = 'file';

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put($this->value);

        $this->assertSame($this->value, $this->cache()->get());
    }

    public function testPut()
    {
        $this->assertSame($this->value, $this->cache()->put($this->value));

        $this->assertSame($this->value, $this->cache()->get());

        $time1 = microtime();
        $this->assertSame($time1, $this->cache()->put($time1));

        $time2 = microtime();
        $this->assertSame($time2, $this->cache()->put($time2));
    }

    public function testRemember()
    {
        $this->assertSame($this->value, $this->cache()->remember($this->value));

        $this->assertSame($this->value, $this->cache()->get());

        $this->assertSame($this->value, $this->cache()->remember(microtime()));
        $this->assertSame($this->value, $this->cache()->remember(microtime()));
    }

    public function testRememberForever()
    {
        $this->assertSame($this->value, $this->cache()->rememberForever($this->value));

        $this->assertSame($this->value, $this->cache()->get());

        $this->assertSame($this->value, $this->cache()->remember(microtime()));
        $this->assertSame($this->value, $this->cache()->remember(microtime()));
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put($this->value);

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
    }

    public function testFlush()
    {
        $tags  = Cache::make()->tags('foo', 'bar');
        $cache = (clone $tags)->key('qwerty');

        $this->assertNull($cache->get());

        $cache->put('asd');

        $this->assertSame('asd', $cache->get());

        $tags->flush();

        $this->assertNull($cache->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertFalse($this->cache()->doesntHave());
    }
}
