<?php

declare(strict_types=1);

namespace Tests\Cache\When\Callables;

use DragonCode\Cache\Services\Cache;
use Tests\Cache\When\BaseTest;

class MultiCallTest extends BaseTest
{
    protected $cache = 'redis';

    public function testGet()
    {
        $value1 = $this->cache(['foo', 'bar']);
        $value2 = $this->cache(['qwe', 'rty']);

        $this->assertNull($value1->get());
        $this->assertNull($value2->get());

        $value1->put(function () {
            return 'Foo';
        });

        $value2->remember(function () {
            return 'Bar';
        });

        $this->assertSame('Foo', $value1->get());
        $this->assertSame('Bar', $value2->get());
        $this->assertSame('Foo', $value1->get());
    }

    public function testForget()
    {
        $value1 = $this->cache(['foo', 'bar']);
        $value2 = $this->cache(['qwe', 'rty']);

        $this->assertNull($value1->get());
        $this->assertNull($value2->get());

        $value1->put(function () {
            return 'Foo';
        });

        $value2->remember(function () {
            return 'Bar';
        });

        $this->assertSame('Foo', $value1->get());
        $this->assertSame('Bar', $value2->get());

        $value1->forget();
        $value2->forget();

        $this->assertNull($value1->get());
        $this->assertNull($value2->get());
    }

    public function testHas()
    {
        $value1 = $this->cache(['foo', 'bar']);
        $value2 = $this->cache(['qwe', 'rty']);

        $this->assertFalse($value1->has());
        $this->assertFalse($value2->has());

        $value1->put(function () {
            return 'Foo';
        });

        $value2->remember(function () {
            return 'Bar';
        });

        $this->assertTrue($value1->has());
        $this->assertTrue($value2->has());
    }

    public function testDoesntHave()
    {
        $value1 = $this->cache(['foo', 'bar']);
        $value2 = $this->cache(['qwe', 'rty']);

        $this->assertTrue($value1->doesntHave());
        $this->assertTrue($value2->doesntHave());

        $value1->put(function () {
            return 'Foo';
        });

        $value2->remember(function () {
            return 'Bar';
        });

        $this->assertFalse($value1->doesntHave());
        $this->assertFalse($value2->doesntHave());
    }

    protected function cache(array $tags = null): Cache
    {
        return Cache::make()
            ->when($this->when)
            ->key(...$tags);
    }
}
