<?php

declare(strict_types=1);

namespace Tests\Support;

use DragonCode\Cache\Facades\Support\TtlBy;
use Tests\Fixtures\Simple\CustomObject;
use Tests\Fixtures\Simple\DragonCodeArrayable;
use Tests\Fixtures\Simple\IlluminateArrayable;
use Tests\Fixtures\Ttl\AsCarbon;
use Tests\Fixtures\Ttl\AsDateTime;
use Tests\Fixtures\Ttl\AsInteger;
use Tests\Fixtures\Ttl\AsString;
use Tests\TestCase;

class TtlByTest extends TestCase
{
    public function testStringAsMinutes()
    {
        $this->assertSame(18000, TtlBy::get(CustomObject::class));
        $this->assertSame(24000, TtlBy::get(DragonCodeArrayable::class));
        $this->assertSame(216000, TtlBy::get(IlluminateArrayable::class));

        $this->assertSame(36000, TtlBy::get('custom'));

        $this->assertSame(216000, TtlBy::get('unknown'));
    }

    public function testStringAsSeconds()
    {
        $this->assertSame(300, TtlBy::get(CustomObject::class, false));
        $this->assertSame(400, TtlBy::get(DragonCodeArrayable::class, false));
        $this->assertSame(3600, TtlBy::get(IlluminateArrayable::class, false));

        $this->assertSame(600, TtlBy::get('custom', false));

        $this->assertSame(3600, TtlBy::get('unknown', false));
    }

    public function testObjectAsMinutes()
    {
        $this->assertSame(18000, TtlBy::get(new CustomObject()));
        $this->assertSame(24000, TtlBy::get(new DragonCodeArrayable()));
        $this->assertSame(216000, TtlBy::get(new IlluminateArrayable()));
    }

    public function testObjectAsSeconds()
    {
        $this->assertSame(300, TtlBy::get(new CustomObject(), false));
        $this->assertSame(400, TtlBy::get(new DragonCodeArrayable(), false));
        $this->assertSame(3600, TtlBy::get(new IlluminateArrayable(), false));
    }

    public function testContractAsMinutes()
    {
        $this->assertSame(3600, TtlBy::get(new AsCarbon('foo')));
        $this->assertSame(7200, TtlBy::get(new AsCarbon('bar')));

        $this->assertSame(3600, TtlBy::get(new AsDateTime('foo')));
        $this->assertSame(7200, TtlBy::get(new AsDateTime('bar')));

        $this->assertSame(600, TtlBy::get(new AsInteger('foo')));
        $this->assertSame(1200, TtlBy::get(new AsInteger('bar')));

        $this->assertSame(600, TtlBy::get(new AsString('foo')));
        $this->assertSame(1200, TtlBy::get(new AsString('bar')));
    }

    public function testContractAsSeconds()
    {
        $this->assertSame(3600, TtlBy::get(new AsCarbon('foo'), false));
        $this->assertSame(7200, TtlBy::get(new AsCarbon('bar'), false));

        $this->assertSame(3600, TtlBy::get(new AsDateTime('foo'), false));
        $this->assertSame(7200, TtlBy::get(new AsDateTime('bar'), false));

        $this->assertSame(10, TtlBy::get(new AsInteger('foo'), false));
        $this->assertSame(20, TtlBy::get(new AsInteger('bar'), false));

        $this->assertSame(10, TtlBy::get(new AsString('foo'), false));
        $this->assertSame(20, TtlBy::get(new AsString('bar'), false));
    }
}
