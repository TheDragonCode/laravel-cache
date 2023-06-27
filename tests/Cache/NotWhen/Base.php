<?php

declare(strict_types=1);

namespace Tests\Cache\NotWhen;

use Tests\TestCase;

abstract class Base extends TestCase
{
    protected bool|object|string $when = false;
}
