<?php

declare(strict_types=1);

namespace Tests\Support;

use DragonCode\Cache\Facades\Support\TtlBy;
use Tests\Fixtures\Simple\CustomObject;
use Tests\Fixtures\Simple\DragonCodeArrayable;
use Tests\Fixtures\Simple\IlluminateArrayable;
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
}
