<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use Illuminate\Support\Str;

class Tag
{
    public function get(array $tags): array
    {
        return array_map(static function (string $tag) {
            return (string) Str::of($tag)->trim()->slug();
        }, $tags);
    }
}
