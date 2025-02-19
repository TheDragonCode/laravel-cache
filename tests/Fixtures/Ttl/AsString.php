<?php

declare(strict_types=1);

namespace Tests\Fixtures\Ttl;

class AsString
{
    public function __construct(
        protected string $value
    ) {}

    public function cacheTtl(): string
    {
        return $this->value === 'foo' ? '10' : '20';
    }
}
