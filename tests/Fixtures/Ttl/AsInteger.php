<?php

declare(strict_types=1);

namespace Tests\Fixtures\Ttl;

class AsInteger
{
    public function __construct(
        protected string $value
    ) {}

    public function cacheTtl(): int
    {
        return $this->value === 'foo' ? 10 : 20;
    }
}
