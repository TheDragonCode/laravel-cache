<?php

declare(strict_types=1);

namespace Tests\Fixtures\Ttl;

use DateTime;
use DateTimeInterface;
use DragonCode\Contracts\Cache\Ttl;

class AsDateTime implements Ttl
{
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function cacheTtl(): DateTimeInterface
    {
        return $this->value === 'foo'
            ? new DateTime('+1 hour')
            : new DateTime('+2 hour');
    }
}
