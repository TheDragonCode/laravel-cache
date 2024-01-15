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

    public function testFlushByKeys()
    {
        $cache1 = Cache::make()->key('foo');
        $cache2 = Cache::make()->key('bar');

        $this->assertNull($cache1->get());
        $this->assertNull($cache2->get());

        $cache1->put('qwe');
        $cache2->put('rty');

        $this->assertSame('qwe', $cache1->get());
        $this->assertSame('rty', $cache2->get());

        $cache1->flush();

        $this->assertNull($cache1->get());
        $this->assertNull($cache2->get());
    }

    public function testFlushByTags()
    {
        $tags1 = Cache::make()->tags('some1');
        $tags2 = Cache::make()->tags('some2');

        $cache1 = (clone $tags1)->key('foo');
        $cache2 = (clone $tags2)->key('bar');

        $this->assertNull($cache1->get());
        $this->assertNull($cache2->get());

        $cache1->put('qwe');
        $cache2->put('rty');

        $this->assertSame('qwe', $cache1->get());
        $this->assertSame('rty', $cache2->get());

        $tags1->flush();

        $this->assertNull($cache1->get());
        $this->assertNull($cache2->get());
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
