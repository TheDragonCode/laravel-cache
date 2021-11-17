<?php

declare(strict_types=1);

namespace Tests\Fixtures\Simple;

use DragonCode\Contracts\Support\Arrayable;

class DragonCodeArrayable implements Arrayable
{
    protected $foo = 'Foo';

    protected $bar = 'Bar';

    public function toArray(): array
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,
        ];
    }
}
