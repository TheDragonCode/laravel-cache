<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen\Ttl;

use Carbon\Carbon;
use DateTime;
use DragonCode\Cache\Services\Cache;
use Tests\Cache\NotWhen\BaseTest;

class TtlTest extends BaseTest
{
    public function testString()
    {
        $this->assertFalse($this->makeCache('10')->has());

        $this->makeCache('10')->put($this->value);

        $this->assertFalse($this->makeCache('10')->has());
    }

    public function testInteger()
    {
        $this->assertFalse($this->makeCache(10)->has());

        $this->makeCache(10)->put($this->value);

        $this->assertFalse($this->makeCache(10)->has());
    }

    public function testCarbon()
    {
        $ttl = Carbon::now()->addDay();

        $this->assertFalse($this->makeCache($ttl)->has());

        $this->makeCache($ttl)->put($this->value);

        $this->assertFalse($this->makeCache($ttl)->has());
    }

    public function testDateTimeInterface()
    {
        $ttl = new DateTime('tomorrow');

        $this->assertFalse($this->makeCache($ttl)->has());

        $this->makeCache($ttl)->put($this->value);

        $this->assertFalse($this->makeCache($ttl)->has());
    }

    public function testClosure()
    {
        $ttl = function () {
            return $this->ttl;
        };

        $this->assertFalse($this->makeCache($ttl)->has());

        $this->makeCache($ttl)->put($this->value);

        $this->assertFalse($this->makeCache($ttl)->has());
    }

    protected function makeCache($ttl): Cache
    {
        return Cache::make()
            ->when($this->when)
            ->ttl($ttl)
            ->key(...$this->keys);
    }
}
