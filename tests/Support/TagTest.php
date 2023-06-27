<?php

declare(strict_types=1);

namespace Tests\Support;

use DragonCode\Cache\Facades\Support\Tag;
use DragonCode\Support\Facades\Application\Version;
use Tests\Concerns\Requestable;
use Tests\Fixtures\Concerns\Dtoable;
use Tests\Fixtures\Dto\CustomDto;
use Tests\Fixtures\Dto\DtoObject;
use Tests\Fixtures\Enums\WithoutValueEnum;
use Tests\Fixtures\Enums\WithValueEnum;
use Tests\Fixtures\Simple\CustomObject;
use Tests\TestCase;

class TagTest extends TestCase
{
    use Dtoable;
    use Requestable;

    protected mixed $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
    ];

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

    public function testArrayable()
    {
        $key = Tag::get($this->dto());

        $expected = ['foo', 'bar'];

        $this->assertSame($expected, $key);
    }

    public function testCustomObject()
    {
        $key = Tag::get([new CustomObject()]);

        $expected = ['foo'];

        $this->assertSame($expected, $key);
    }

    public function testMultiObjectArrays()
    {
        $key = Tag::get([
            'qwe',
            'rty',
            DtoObject::make(['foo' => 'Foo']),
            DtoObject::make(['bar' => 'Bar']),
            CustomDto::make(['wasd' => 'WASD']),
            new CustomObject(),
        ]);

        $expected = ['qwe', 'rty', 'foo', 'bar', 'wasd', 'foo'];

        $this->assertSame($expected, $key);
    }

    public function testFormRequest()
    {
        $key = Tag::get($this->formRequest([
            'foo' => 'Foo',
            'bar' => 'Bar',
            'baz' => 'Baz',
        ]));

        $expected = ['foo', 'bar'];

        $this->assertSame($expected, $key);
    }

    public function testEnum()
    {
        if (Version::of('8.1.0')->lt(phpversion())) {
            $this->assertTrue(true);

            return;
        }

        $key = Tag::get([WithoutValueEnum::foo, WithValueEnum::bar]);

        $expected = ['foo', 'bar'];

        $this->assertSame($expected, $key);
    }
}
