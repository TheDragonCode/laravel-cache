<?php

declare(strict_types=1);

namespace Tests\Cache\EnabledConfig;

use stdClass;
use Tests\Fixtures\Simple\CustomObject;
use Tests\TestCase;

class ObjectTest extends TestCase
{
    protected bool|object|string $when = true;

    public function testExists()
    {
        $this->when = new CustomObject();

        config(['cache.enabled.' . CustomObject::class => true]);

        $this->assertFalse($this->cache()->has());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }

    public function testDoesntExists()
    {
        $this->when = new CustomObject();

        $this->assertFalse($this->cache()->has());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }

    public function testExistsStdClass()
    {
        $this->when = (object) ['foo' => 'bar'];

        config(['cache.enabled.' . stdClass::class => true]);

        $this->assertFalse($this->cache()->has());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }

    public function testDoesntExistsStdClass()
    {
        $this->when = (object) ['foo' => 'bar'];

        $this->assertFalse($this->cache()->has());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }
}
