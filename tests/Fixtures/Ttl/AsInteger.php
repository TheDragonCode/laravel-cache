<?php

declare(strict_types=1);

namespace Tests\Fixtures\Ttl;

use DragonCode\Contracts\Cache\Ttl;

class AsInteger implements Ttl
{
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function cacheTtl(): int
    {
        return $this->value === 'foo' ? 10 : 20;
    }
}
