<?php

declare(strict_types=1);

namespace Tests\Support;

use DragonCode\Cache\Facades\Support\Key;
use Tests\Fixtures\Concerns\Dtoable;
use Tests\Fixtures\Dto\CustomDto;
use Tests\Fixtures\Dto\DtoObject;
use Tests\TestCase;

class KeyTest extends TestCase
{
    use Dtoable;

    protected $value = [
        'foo' => 'Foo',
        'bar' => 'Bar',
    ];

    public function testString()
    {
        $key = Key::get(':', ['Foo', 'Bar', 'Baz']);

        $expected = '1356c67d7ad1638d816bfb822dd2c25d:ddc35f88fa71b6ef142ae61f35364653:f8dce67f2c94388282ed3fa797968a7c';

        $this->assertSame($expected, $key);
    }

    public function testNumeric()
    {
        $key = Key::get(':', [1, 2, 3]);

        $expected = 'c4ca4238a0b923820dcc509a6f75849b:c81e728d9d4c2f636f067f89cc14862c:eccbc87e4b5ce2fe28308fd9f2a7baf3';

        $this->assertSame($expected, $key);
    }

    public function testArray()
    {
        $key = Key::get(':', [
            ['foo' => 'Foo'],
            ['bar' => 'Bar'],
            [['Baz', 'Qwerty']],
        ]);

        $expected = '1356c67d7ad1638d816bfb822dd2c25d:ddc35f88fa71b6ef142ae61f35364653:f8dce67f2c94388282ed3fa797968a7c:acbd9ab2f68bea3f5291f825416546a1';

        $this->assertSame($expected, $key);
    }

    public function testCombine()
    {
        $key = Key::get(':', [1, 'Foo', [['Bar', 'Baz']]]);

        $expected = 'c4ca4238a0b923820dcc509a6f75849b:1356c67d7ad1638d816bfb822dd2c25d:ddc35f88fa71b6ef142ae61f35364653:f8dce67f2c94388282ed3fa797968a7c';

        $this->assertSame($expected, $key);
    }

    public function testArrayable()
    {
        $key = Key::get(':', $this->dto());

        $expected = '1356c67d7ad1638d816bfb822dd2c25d:ddc35f88fa71b6ef142ae61f35364653';

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
        ]);

        // Before hashing, the keys look like this:
        // qwe:rty:Foo:Bar:WASD

        $expected =
            '76d80224611fc919a5d54f0ff9fba446:24113791d2218cb84c9f0462e91596ef:' .
            '1356c67d7ad1638d816bfb822dd2c25d:ddc35f88fa71b6ef142ae61f35364653:' .
            '91412421a30e87ce15a4f10ea39f6682';

        $this->assertSame($expected, $key);
    }
}
