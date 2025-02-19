<?php

declare(strict_types=1);

namespace Tests\Fixtures\Dto;

use Illuminate\Contracts\Support\Arrayable;

class CustomDto implements Arrayable
{
    public $wasd;

    public static function make(array $values): CustomDto
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
            'wasd' => $this->wasd,
        ];
    }
}
