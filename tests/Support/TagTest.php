<?php

declare(strict_types=1);

namespace Tests\Support;

use DragonCode\Cache\Facades\Support\Tag;
use Tests\TestCase;

class TagTest extends TestCase
{
    public function testString()
    {
        $tags = Tag::get(['FoO', 'Bar', 'a-aZ', ' Qwe Rt_y']);

        $expected = ['foo', 'bar', 'a-az', 'qwe-rt-y'];

        $this->assertSame($expected, $tags);
    }

    public function testNumeric()
    {
        $tags = Tag::get([1, 2, 3]);

        $expected = ['1', '2', '3'];

        $this->assertSame($expected, $tags);
    }

    public function testArray()
    {
        $tags = Tag::get([
            ['foo' => 'Foo'],
            ['bar' => 'Bar'],
            [['Baz', 'КвертИ']],
        ]);

        $expected = ['foo', 'bar', 'baz', 'kverti'];

        $this->assertSame($expected, $tags);
    }
}
