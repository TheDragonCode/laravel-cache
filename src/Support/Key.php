<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

class Key
{
    public function make(string $separator, ...$values): string
    {
        $hashed = $this->hash($values);

        return $this->compile($hashed, $separator);
    }

    protected function hash(array $values): array
    {
        return array_map(static function ($value) {
            return md5($value);
        }, $values);
    }

    protected function compile(array $values, string $separator): string
    {
        return implode($separator, $values);
    }
}
