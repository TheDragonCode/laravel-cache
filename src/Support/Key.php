<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Support\Facades\Helpers\Ables\Arrayable;

class Key
{
    public function get(string $separator, array $values): string
    {
        $hashed = $this->hash($values);

        return $this->compile($hashed, $separator);
    }

    protected function hash(array $values): array
    {
        return Arrayable::of($values)
            ->flatten()
            ->map(static function ($value) {
                return md5((string) $value);
            })->get();
    }

    protected function compile(array $values, string $separator): string
    {
        return implode($separator, $values);
    }
}
