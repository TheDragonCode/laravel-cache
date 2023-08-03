<?php

declare(strict_types=1);

namespace Tests\Support;

use Carbon\Carbon;
use DragonCode\Cache\Facades\Support\Key;
use DragonCode\Support\Facades\Application\Version;
use Tests\Concerns\Requestable;
use Tests\Fixtures\Concerns\Dtoable;
use Tests\Fixtures\Dto\CustomDto;
use Tests\Fixtures\Dto\DtoObject;
use Tests\Fixtures\Enums\WithoutValueEnum;
use Tests\Fixtures\Enums\WithValueEnum;
use Tests\Fixtures\Models\User;
use Tests\Fixtures\Simple\CustomObject;
use Tests\TestCase;

class KeyTest extends TestCase
{
    use Dtoable;
    use Requestable;

    protected mixed $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
    ];

    public function testString()
    {
        $key = Key::get(':', ['Foo', 'Bar', 'Baz']);

        $expected =
            '17358f5eb750c32289df798e7766e830:64db6856f253b7bf17202a3dd3254fc1:05797d9d2d667864e94e07ba8df60840';

        $this->assertSame($expected, $key);
    }

    public function testNumeric()
    {
        $key = Key::get(':', [1, 2, 3]);

        $expected =
            'd944267ac25276f12cb03fc698810d94:7b2fb106352b24c6dd644a8cdf200295:d8526ab50063e2025ef690f730cd5542';

        $this->assertSame($expected, $key);
    }

    public function testArray()
    {
        $key = Key::get(':', [
            ['foo' => 'Foo'],
            ['bar' => 'Bar'],
            [['Baz', 'Qwerty']],
        ]);

        $expected = implode(':', [
            'b58721335d52d66a9486072fe3383ccf',
            'f61f09aec2b68da240f6680a6fc88c6a',
            'a13960759cc35a02e91fafb356f491c6',
            '70f48dde06f86de7fae03486c277f597',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testBoolean()
    {
        $key = Key::get(':', [true, false]);

        $expected = implode(':', [
            'd944267ac25276f12cb03fc698810d94',
            'bcb8c4703eae71d5d05c0a6eec1f7daa',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testCombine()
    {
        $key = Key::get(':', [1, 'Foo', [['Bar', 'Baz']]]);

        $expected = implode(':', [
            'd944267ac25276f12cb03fc698810d94',
            '5bfe89f7c2ace87ef1c208c3d95fc1b6',
            'a6426f0db8f32e1156366c7ffe317a6c',
            '6e30ad368454c1fdd71d181f47314222',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testArrayable()
    {
        $key = Key::get(':', $this->dto());

        $expected = implode(':', [
            'b58721335d52d66a9486072fe3383ccf',
            '8a5c22700ece9adc6c0265fa4af575f1',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testCustomObject()
    {
        $key = Key::get(':', [new CustomObject()]);

        $expected = 'b58721335d52d66a9486072fe3383ccf';

        $this->assertSame($expected, $key);
    }

    public function testMultiObjectArrays()
    {
        $key = Key::get(':', [
            'qwe',
            'rty',
            DtoObject::make(['foo' => 'Foo']),
            DtoObject::make(['bar' => 'Bar']),
            CustomDto::make(['wasd' => 'WASD']),
            new CustomObject(),
        ]);

        // Before hashing, the keys look like this:
        // qwe:rty:Foo:Bar:WASD:Foo

        $expected = implode(':', [
            '6ced27e919d8e040c44929d72fffb681',
            'b619de70b824374bf86438df3b059bca',
            'bc5ea0aa608c8e0ef8175083e334abff',
            '89f5d16cceb669516a0d37c6f2d47df8',
            '4d1c034a3e1f42f73339950f3a416c46',
            'eb499c88d41a72f17278348308b4bae8',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testEmpties()
    {
        $key = Key::get(':', [null, '', 0, [], false]);

        $expected = implode(':', [
            '2d4bab7f33ac57126deb8cde12a0c2ae',
            '3e3a3e1902376d96020b11c67bec7a08',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testModelKey()
    {
        $key = Key::get(':', [User::class, 'foo', 'bar']);

        $expected =
            '87789eae95facc4a5bfdeb957b860942:086f76c144511e1198c29a261e87ca50:2b72000f7b07c51cbbe0e7f85a19597e';

        $this->assertSame($expected, $key);
    }

    public function testCarbon()
    {
        $key = Key::get(':', [
            Carbon::parse('2022-10-07 14:00:00'),
            Carbon::parse('2022-10-07 15:00:00'),
        ]);

        $expected = '67f1a84c86633483bea1d2080767711c:aeab04bbac549fe6268a7e12ef761165';

        $this->assertSame($expected, $key);
    }

    public function testFormRequest()
    {
        $key = Key::get(
            ':',
            $this->formRequest([
                'foo' => 'Foo',
                'bar' => 'Bar',
                'baz' => 'Baz',
            ])
        );

        $expected = implode(':', [
            'b58721335d52d66a9486072fe3383ccf',
            '8a5c22700ece9adc6c0265fa4af575f1',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testEnum()
    {
        if (Version::of('8.1.0')->lt(phpversion())) {
            $this->assertTrue(true);

            return;
        }

        $key = Key::get(':', [WithoutValueEnum::foo, WithValueEnum::bar]);

        $expected = implode(':', [
            '33e35b61ea46b126d2a6bf81acda8724',
            '660a13c00e04c0d3ffb4dbf02a84a07a',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testWithoutHash()
    {
        $keys = [
            ['foo' => 'Foo'],
            ['bar' => 'Bar'],
            [['Baz', 'Qwerty']],
        ];

        $key = Key::get(':', $keys, false);

        $expected = '0.foo=Foo:1.bar=Bar:2.0.0=Baz:2.0.1=Qwerty';

        $this->assertSame($expected, $key);
    }
}
