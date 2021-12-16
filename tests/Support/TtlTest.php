<?php

declare(strict_types=1);

namespace Tests\Support;

use Carbon\Carbon;
use DateTime;
use DragonCode\Cache\Facades\Support\Ttl;
use Tests\TestCase;

class TtlTest extends TestCase
{
    public function testString()
    {
        $this->assertSame(600, Ttl::fromMinutes('10'));
        $this->assertSame(10, Ttl::fromSeconds('10'));
    }

    public function testInteger()
    {
        $this->assertSame(600, Ttl::fromMinutes(10));
        $this->assertSame(10, Ttl::fromSeconds(10));
    }

    public function testCarbon()
    {
        $ttl = Carbon::now()->addDay();

        $expected = Carbon::now()->diffInRealSeconds($ttl);

        $this->assertSame($expected, Ttl::fromMinutes($ttl));
        $this->assertSame($expected, Ttl::fromSeconds($ttl));
    }

    public function testDateTimeInterface()
    {
        $ttl = new DateTime('tomorrow');

        $expected = Carbon::now()->diffInRealSeconds($ttl);

        $this->assertSame($expected, Ttl::fromMinutes($ttl));
        $this->assertSame($expected, Ttl::fromSeconds($ttl));
    }

    public function testClosure()
    {
        $ttl = function () {
            return $this->ttl;
        };

        $this->assertSame(3600, Ttl::fromMinutes($ttl));
        $this->assertSame(60, Ttl::fromSeconds($ttl));
    }
}
