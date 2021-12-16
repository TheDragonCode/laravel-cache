<?php

declare(strict_types=1);

namespace Tests\Fixtures\Ttl;

use Carbon\Carbon;
use DragonCode\Contracts\Cache\Ttl;

class AsCarbon implements Ttl
{
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function cacheTtl(): Carbon
    {
        return $this->value === 'foo'
            ? Carbon::now()->addHour()
            : Carbon::now()->addHours(2);
    }
}
