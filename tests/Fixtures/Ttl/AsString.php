<?php

declare(strict_types=1);

namespace Tests\Fixtures\Ttl;

use DragonCode\Contracts\Cache\Ttl;

class AsString implements Ttl
{
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function cacheTtl(): string
    {
        return $this->value === 'foo' ? '10' : '20';
    }
}
