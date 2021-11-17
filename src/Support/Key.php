<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Cache\Concerns\Arrayable;
use DragonCode\Contracts\DataTransferObject\DataTransferObject;

class Key
{
    use Arrayable;

    /**
     * @param  string  $separator
     * @param  array|\DragonCode\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Arrayable|\ArrayObject|DataTransferObject  $values
     *
     * @return string
     */
    public function get(string $separator, $values): string
    {
        $values = $this->toArray($values);

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
