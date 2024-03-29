<?php

declare(strict_types=1);

namespace Tests\Cache\EnabledConfig;

use stdClass;
use Tests\Fixtures\Simple\CustomObject;
use Tests\TestCase;

class DisabledTest extends TestCase
{
    protected bool|object|string $when = true;

    public function testBool()
    {
        $this->when = false;

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->doesntHave());
    }

    public function testString()
    {
        $this->when = 'foo';

        config(['cache.enabled.foo' => false]);

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->doesntHave());
    }

    public function testStringObject()
    {
        $this->when = CustomObject::class;

        config(['cache.enabled.' . CustomObject::class => false]);

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->doesntHave());
    }

    public function testObject()
    {
        $this->when = new CustomObject();

        config(['cache.enabled.' . CustomObject::class => false]);

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->doesntHave());
    }

    public function testStdClass()
    {
        $this->when = (object) ['foo' => 'bar'];

        config(['cache.enabled.' . stdClass::class => false]);

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->doesntHave());
    }
}
