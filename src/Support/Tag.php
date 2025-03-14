<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use ArrayObject;
use DragonCode\Cache\Concerns\Arrayable;
use DragonCode\Support\Facades\Helpers\Str;

class Tag
{
    use Arrayable;

    /**
     * @param  array|\Illuminate\Contracts\Support\Arrayable|ArrayObject  $tags
     */
    public function get(mixed $tags): array
    {
        return $this->map(
            $this->toArray($tags)
        );
    }

    protected function map(array $tags): array
    {
        return $this->arrayMap($tags, fn (string $tag) => $this->slug($tag));
    }

    protected function slug(string $tag): string
    {
        return Str::of($tag)->trim()->slug()->toString();
    }
}
