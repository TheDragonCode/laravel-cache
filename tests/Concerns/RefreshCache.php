<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Support\Facades\Cache;

trait RefreshCache
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }
}
