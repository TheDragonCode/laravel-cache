<?php

declare(strict_types=1);

namespace Tests\Fixtures\Dto;

use DragonCode\SimpleDataTransferObject\DataTransferObject;

class DtoObject extends DataTransferObject
{
    public $foo;

    public $bar;

    protected $baz;

    protected $baq;
}
