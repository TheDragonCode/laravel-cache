<?php

declare(strict_types=1);

namespace Tests\Support;

use Carbon\Carbon;
use DateTime;
use DragonCode\Cache\Facades\Support\Ttl;
use Tests\Fixtures\Simple\CustomObject;
use Tests\Fixtures\Simple\DragonCodeArrayable;
use Tests\Fixtures\Simple\IlluminateArrayable;
use Tests\Fixtures\Ttl\AsCarbon;
use Tests\Fixtures\Ttl\AsDateTime;
use Tests\Fixtures\Ttl\AsInteger;
use Tests\Fixtures\Ttl\AsString;
use Tests\TestCase;

class TtlTest extends TestCase
{
    public function testString(): void
    {
        $this->assertSame(600, Ttl::fromMinutes('10'));
        $this->assertSame(10, Ttl::fromSeconds('10'));
    }

    public function testInteger(): void
    {
        $this->assertSame(600, Ttl::fromMinutes(10));
        $this->assertSame(10, Ttl::fromSeconds(10));
    }

    public function testCarbon(): void
    {
        $ttl = Carbon::now()->addDay();

        $expected = (int) Carbon::now()->diffInSeconds($ttl);

        $this->assertSame($expected, Ttl::fromMinutes($ttl));
        $this->assertSame($expected, Ttl::fromSeconds($ttl));
    }

    public function testDateTimeInterface(): void
    {
        $ttl = new DateTime('tomorrow');

        $expected = (int) Carbon::now()->diffInSeconds($ttl);

        $this->assertSame($expected, Ttl::fromMinutes($ttl));
        $this->assertSame($expected, Ttl::fromSeconds($ttl));
    }

    public function testClosure(): void
    {
        $ttl = function () {
            return $this->ttl;
        };

        $this->assertSame(3600, Ttl::fromMinutes($ttl));
        $this->assertSame(60, Ttl::fromSeconds($ttl));
    }

    public function testObjectAsString(): void
    {
        $this->assertSame(18000, Ttl::fromMinutes(CustomObject::class));
        $this->assertSame(300, Ttl::fromSeconds(CustomObject::class));

        $this->assertSame(24000, Ttl::fromMinutes(DragonCodeArrayable::class));
        $this->assertSame(400, Ttl::fromSeconds(DragonCodeArrayable::class));

        $this->assertSame(216000, Ttl::fromMinutes(IlluminateArrayable::class));
        $this->assertSame(3600, Ttl::fromSeconds(IlluminateArrayable::class));

        $this->assertSame(36000, Ttl::fromMinutes('custom'));
        $this->assertSame(600, Ttl::fromSeconds('custom'));

        $this->assertSame(216000, Ttl::fromMinutes('unknown'));
        $this->assertSame(3600, Ttl::fromSeconds('unknown'));
    }

    public function testObjectAsObject(): void
    {
        $this->assertSame(18000, Ttl::fromMinutes(new CustomObject()));
        $this->assertSame(300, Ttl::fromSeconds(new CustomObject()));

        $this->assertSame(24000, Ttl::fromMinutes(new DragonCodeArrayable()));
        $this->assertSame(400, Ttl::fromSeconds(new DragonCodeArrayable()));

        $this->assertSame(216000, Ttl::fromMinutes(new IlluminateArrayable()));
        $this->assertSame(3600, Ttl::fromSeconds(new IlluminateArrayable()));

        $this->assertSame(36000, Ttl::fromMinutes((object) ['foo' => 'Foo']));
        $this->assertSame(600, Ttl::fromSeconds((object) ['foo' => 'Foo']));
    }

    public function testInstances(): void
    {
        $this->assertSame(3599, Ttl::fromMinutes((new AsCarbon('foo'))->cacheTtl()));
        $this->assertSame(7199, Ttl::fromSeconds((new AsCarbon('bar'))->cacheTtl()));

        $this->assertSame(3599, Ttl::fromMinutes((new AsDateTime('foo'))->cacheTtl()));
        $this->assertSame(7199, Ttl::fromSeconds((new AsDateTime('bar'))->cacheTtl()));

        $this->assertSame(600, Ttl::fromMinutes((new AsInteger('foo'))->cacheTtl()));
        $this->assertSame(20, Ttl::fromSeconds((new AsInteger('bar'))->cacheTtl()));

        $this->assertSame(600, Ttl::fromMinutes((new AsString('foo'))->cacheTtl()));
        $this->assertSame(20, Ttl::fromSeconds((new AsString('bar'))->cacheTtl()));
    }
}
