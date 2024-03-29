<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use ArrayObject;
use DragonCode\Cache\Concerns\Arrayable;
use DragonCode\Contracts\Support\Arrayable as DragonArrayable;
use Illuminate\Contracts\Support\Arrayable as IlluminateArrayable;

class Key
{
    use Arrayable;

    public function get(
        string $separator,
        array|ArrayObject|DragonArrayable|IlluminateArrayable $values,
        bool $hash = true
    ): string {
        $values = $this->toArray($values);

        $hashed = $this->hash($values, $hash);

        return $this->compile($hashed, $separator);
    }

    protected function hash(array $values, bool $hash = true): array
    {
        return $this->arrayFlattenKeysMap($values, fn (mixed $value) => $hash ? md5($value) : $value);
    }

    protected function compile(array $values, string $separator): string
    {
        return implode($separator, $values);
    }
}
