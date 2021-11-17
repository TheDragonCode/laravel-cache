<?php

declare(strict_types=1);

namespace Tests\Fixtures\Concerns;

use DragonCode\Contracts\DataTransferObject\DataTransferObject;
use Tests\Fixtures\Dto\DtoObject;

trait Dtoable
{
    protected function dto(): DataTransferObject
    {
        return DtoObject::make(array_merge($this->value, [
            'baz' => 'Baz',
            'baq' => 'Baq',
            'qwe' => 'Rty',
        ]));
    }
}
