<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use DragonCode\Cache\Concerns\Arrayable;
use DragonCode\Support\Facades\Helpers\Ables\Stringable;

class Tag
{
    use Arrayable;

    public function get(array $tags): array
    {
        return $this->arrayMap($tags, function (string $tag) {
            return $this->slug($tag);
        });
    }

    protected function slug(string $tag): string
    {
        return (string) Stringable::of($tag)->trim()->slug();
    }
}
