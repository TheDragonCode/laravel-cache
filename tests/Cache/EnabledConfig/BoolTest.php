<?php

declare(strict_types=1);

namespace Tests\Cache\EnabledConfig;

use Tests\TestCase;

class BoolTest extends TestCase
{
    protected mixed $when = true;

    public function testEnabled()
    {
        $this->when = true;

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }

    public function testDisabled()
    {
        $this->when = false;

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->doesntHave());
    }
}