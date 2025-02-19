<?php

declare(strict_types=1);

namespace Tests\Fixtures\Ttl;

use Carbon\Carbon;

class AsCarbon
{
    public function __construct(
        protected string $value
    ) {}

    public function cacheTtl(): Carbon
    {
        return $this->value === 'foo'
            ? Carbon::now()->addHour()
            : Carbon::now()->addHours(2);
    }
}
