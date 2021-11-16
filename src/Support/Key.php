<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Cache\Concerns\Arrayable;

class Key
{
    use Arrayable;

    public function get(string $separator, array $values): string
    {
        $hashed = $this->hash($values);

        return $this->compile($hashed, $separator);
    }

    protected function hash(array $values): array
    {
        return $this->arrayMap($values, static function ($value) {
            return md5((string) $value);
        });
    }

    protected function compile(array $values, string $separator): string
    {
        return implode($separator, $values);
    }
}
