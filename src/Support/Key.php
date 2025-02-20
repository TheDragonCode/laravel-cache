<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use ArrayObject;
use DragonCode\Cache\Concerns\Arrayable;
use Illuminate\Contracts\Support\Arrayable as IlluminateArrayable;

use function hash;
use function implode;

class Key
{
    use Arrayable;

    public function get(
        string $separator,
        array|ArrayObject|IlluminateArrayable $values,
        bool $hash = true
    ): string {
        $values = $this->toArray($values);

        $hashed = $this->hash($values, $hash);

        return implode($separator, $hashed);
    }

    protected function hash(array $values, bool $hash = true): array
    {
        return $this->arrayFlattenKeysMap($values, static fn (mixed $value) => $hash ? hash('xxh128', $value) : $value);
    }
}
