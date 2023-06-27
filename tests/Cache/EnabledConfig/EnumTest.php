<?php

declare(strict_types=1);

namespace Tests\Cache\EnabledConfig;

use DragonCode\Support\Facades\Application\Version;
use Tests\Fixtures\Enums\WithValueEnum;
use Tests\TestCase;

class EnumTest extends TestCase
{
    protected mixed $when = true;

    public function testEnabled()
    {
        if (Version::of('8.1.0')->lt(phpversion())) {
            $this->assertTrue(true);

            return;
        }

        $this->when = WithValueEnum::bar;

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
    }

    public function testDisabled()
    {
        if (Version::of('8.1.0')->lt(phpversion())) {
            $this->assertTrue(true);

            return;
        }

        $this->when = WithValueEnum::bar;

        config(['cache.enabled.' . WithValueEnum::bar->value => false]);

        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->doesntHave());
    }
}
