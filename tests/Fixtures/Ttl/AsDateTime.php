<?php

declare(strict_types=1);

namespace Tests\Fixtures\Ttl;

use DateTime;
use DateTimeInterface;

class AsDateTime
{
    public function __construct(
        protected string $value
    ) {}

    public function cacheTtl(): DateTimeInterface
    {
        return $this->value === 'foo'
            ? new DateTime('+1 hour')
            : new DateTime('+2 hour');
    }
}
