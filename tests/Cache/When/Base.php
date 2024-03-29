<?php

declare(strict_types=1);

namespace Tests\Cache\When;

use Tests\TestCase;

abstract class Base extends TestCase
{
    protected bool|object|string $when = true;
}
