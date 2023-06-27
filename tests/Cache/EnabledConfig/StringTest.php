<?php

declare(strict_types=1);

namespace Tests\Cache\EnabledConfig;

use Tests\Fixtures\Simple\CustomObject;
use Tests\TestCase;

class StringTest extends TestCase
{
    protected mixed $when = true;

    public function testExists()
    {
        $this->when = 'foo';

        config(['cache.enabled.foo' => true]);

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }

    public function testExistsClass()
    {
        $this->when = CustomObject::class;

        config(['cache.enabled.' . CustomObject::class => true]);

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }

    public function testDoesntExists()
    {
        $this->when = 'bar';

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }

    public function testDoesntExistsClass()
    {
        $this->when = CustomObject::class;

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }
}
