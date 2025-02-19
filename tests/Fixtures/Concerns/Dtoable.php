<?php

declare(strict_types=1);

namespace Tests\Fixtures\Concerns;

use Tests\Fixtures\Dto\DtoObject;

trait Dtoable
{
    protected function dto(): DtoObject
    {
        return DtoObject::make(array_merge($this->value, [
            'baz' => 'Baz',
            'baq' => 'Baq',
            'qwe' => 'Rty',
        ]));
    }
}
