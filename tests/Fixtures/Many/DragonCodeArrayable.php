<?php

declare(strict_types=1);

namespace Tests\Fixtures\Many;

use DragonCode\Contracts\Support\Arrayable;
use Tests\Fixtures\Simple\DragonCodeArrayable as Simple;

class DragonCodeArrayable implements Arrayable
{
    protected $foo = 'Foo';

    protected $bar = 'Bar';

    public function toArray(): array
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,

            'baz' => new Simple(),
        ];
    }
}
