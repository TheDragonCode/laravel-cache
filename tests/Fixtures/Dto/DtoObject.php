<?php

declare(strict_types=1);

namespace Tests\Fixtures\Dto;

use Illuminate\Contracts\Support\Arrayable;

class DtoObject implements Arrayable
{
    public $foo;

    public $bar;

    protected $baz;

    protected $baq;

    public static function make(array $values): DtoObject
    {
        $object = new static();

        foreach ($values as $key => $value) {
            if (property_exists($object, $key)) {
                $object->{$key} = $value;
            }
        }

        return $object;
    }

    public function toArray(): array
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,
        ];
    }
}
