<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use ArrayObject;
use DragonCode\Cache\Concerns\Arrayable;
use DragonCode\Contracts\DataTransferObject\DataTransferObject;
use DragonCode\Support\Facades\Helpers\Ables\Stringable;

class Tag
{
    use Arrayable;

    /**
     * @param array|\DragonCode\Contracts\Support\Arrayable|\Illuminate\Contracts\Support\Arrayable|ArrayObject|DataTransferObject $tags
     *
     * @return array
     */
    public function get($tags): array
    {
        $tags = $this->toArray($tags);

        return $this->map($tags);
    }

    protected function map(array $tags): array
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
