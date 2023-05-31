<?php

declare(strict_types=1);

namespace Tests\Cache\When\Simple;

use DragonCode\Cache\Services\Cache;
use Tests\Cache\When\Base;

class ChainTest extends Base
{
    protected $cache = 'redis';

    public function testMain()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->cache()
            ->call(fn (Cache $cache) => $this->assertTrue($cache->has()))
            ->forget()
            ->call(fn (Cache $cache) => $this->assertTrue($cache->doesntHave()));
    }

    public function testWhen()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->cache()
            ->call(fn (Cache $cache) => $this->assertTrue(false), false)
            ->call(fn (Cache $cache) => $this->assertTrue(false), fn () => false)
            ->call(fn (Cache $cache) => $this->assertTrue(false), 0)
            ->call(fn (Cache $cache) => $this->assertTrue(false), fn () => 0);
    }
}
