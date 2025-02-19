<?php

declare(strict_types=1);

namespace Tests\Fixtures\Many;

use Illuminate\Contracts\Support\Arrayable;
use Tests\Fixtures\Simple\DragonCodeArrayable as SimpleDragon;
use Tests\Fixtures\Simple\IlluminateArrayable as SimpleIlluminate;

class MixedArrayable implements Arrayable
{
    protected $foo = 'Foo';

    protected $bar = 'Bar';

    public function toArray(): array
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,

            'baz' => new SimpleDragon(),
            'baq' => new SimpleIlluminate(),
        ];
    }
}
