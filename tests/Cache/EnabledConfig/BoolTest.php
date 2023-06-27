<?php

declare(strict_types=1);

namespace Tests\Cache\EnabledConfig;

use Tests\TestCase;

class BoolTest extends TestCase
{
    protected bool|object|string $when = true;

    public function testEnabled()
    {
        $this->when = true;

        $this->assertFalse($this->cache()->has());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }

    public function testDisabled()
    {
        $this->when = false;

        $this->assertFalse($this->cache()->has());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->doesntHave());
    }
}
