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
    public function testString()
    {
        $this->assertSame(300, TtlBy::get(CustomObject::class));
        $this->assertSame(400, TtlBy::get(DragonCodeArrayable::class));
        $this->assertSame(3600, TtlBy::get(IlluminateArrayable::class));

        $this->assertSame(600, TtlBy::get('custom'));

        $this->assertSame(3600, TtlBy::get('unknown'));
    }

    public function testObject()
    {
        $this->assertSame(300, TtlBy::get(new CustomObject()));
        $this->assertSame(400, TtlBy::get(new DragonCodeArrayable()));
        $this->assertSame(3600, TtlBy::get(new IlluminateArrayable()));
    }
}
