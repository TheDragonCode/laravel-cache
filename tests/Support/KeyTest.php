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
use Tests\Fixtures\Simple\CustomObjectWithoutProperties;
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

        $expected
            = '066844617672172f2ae39d2d5913ef4c:bcba27d6e802f5996451ba64a228b6c6:d7ecefa0b151a97860dc4484f7f8fa4f';

        $this->assertSame($expected, $key);
    }

    public function testNumeric()
    {
        $key = Key::get(':', [1, 2, 3]);

        $expected
            = 'c86e3110a6d26fe560ae738cb6e8b35a:ff31a4bdf27d72cd226daa17502de42f:514c9ac227530b28cef0ec15f1078cf3';

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
            '787f1b60e54c7c9840578e809a5bb483',
            'c3df2edd586890488af77f9d1b76624c',
            '630b6a6167f3c10da0087deddfb0bc78',
            '23ce8e493c657f862a59d405d5ae4a22',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testBoolean()
    {
        $key = Key::get(':', [true, false]);

        $expected = implode(':', [
            'c86e3110a6d26fe560ae738cb6e8b35a',
            '7fefa631bfe2475ff417717268a34195',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testCombine()
    {
        $key = Key::get(':', [1, 'Foo', [['Bar', 'Baz']]]);

        $expected = implode(':', [
            'c86e3110a6d26fe560ae738cb6e8b35a',
            '832eed4c5bf858df13ee59b67aa75b1d',
            'd10414ec90de1e952a615a2c3d656268',
            'f16e7b1ceb2ae177dea9829b29e101c9',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testArrayable()
    {
        $key = Key::get(':', $this->dto());

        $expected = implode(':', [
            '787f1b60e54c7c9840578e809a5bb483',
            '0e64b00cd661261763e451808a05677e',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testCustomObjects()
    {
        $key1 = Key::get(':', [new CustomObject()]);
        $key2 = Key::get(':', [new CustomObjectWithoutProperties()]);

        $this->assertSame('787f1b60e54c7c9840578e809a5bb483', $key1);
        $this->assertSame('f9c1f3c1439cb8459d9025a9e597b7c9', $key2);
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
            '00a922fefaabdc276830745c624b6d04',
            'b1c6c02a6b5e40c95e7787f631ff6948',
            'b2dd9e7a3140ce9496ac559a0f4412b4',
            '08607c1a9f3d6989110f468d49ed2a53',
            'd78af385c25b9903f9f95ba1823a1b8f',
            '7d25a0a73048ee2de0f7c9a1d1e240d9',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testEmpties()
    {
        $key = Key::get(':', [null, '', 0, [], false]);

        $expected = implode(':', [
            '43d447ef566417fd13017147aa70f410',
            '0f39f9a21e0c7acb31e59ef0559557dd',
        ]);

        $this->assertSame($expected, $key);
    }

    public function testModelKey()
    {
        $key = Key::get(':', [User::class, 'foo', 'bar']);

        $expected
            = 'cbd255cf79e6ffc7245d84ec28e86873:3eaad76f0a1e8fe159522d2873cd102f:bf7ab0d983046b032fc9c22b17179bff';

        $this->assertSame($expected, $key);
    }

    public function testCarbon()
    {
        $key = Key::get(':', [
            Carbon::parse('2022-10-07 14:00:00'),
            Carbon::parse('2022-10-07 15:00:00'),
        ]);

        $expected = '7485b8d070b3891642636988ae60923c:0dc51b5eaafc5968afc8c81e5b8d50f3';

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
            '787f1b60e54c7c9840578e809a5bb483',
            '0e64b00cd661261763e451808a05677e',
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
            'f44e232cceb317df905da38294debd48',
            '9072f0509e45df294401beab989eb873',
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
