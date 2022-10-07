<?php

declare(strict_types=1);

namespace Tests\Support;

use Carbon\Carbon;
use DragonCode\Cache\Facades\Support\Key;
use Tests\Fixtures\Concerns\Dtoable;
use Tests\Fixtures\Dto\CustomDto;
use Tests\Fixtures\Dto\DtoObject;
use Tests\Fixtures\Models\User;
use Tests\Fixtures\Simple\CustomObject;
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

    public function testCustomObject()
    {
        $key = Key::get(':', [new CustomObject()]);

        $expected = '1356c67d7ad1638d816bfb822dd2c25d';

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

        $expected
            = '76d80224611fc919a5d54f0ff9fba446:24113791d2218cb84c9f0462e91596ef:'
            . '1356c67d7ad1638d816bfb822dd2c25d:ddc35f88fa71b6ef142ae61f35364653:'
            . '91412421a30e87ce15a4f10ea39f6682:1356c67d7ad1638d816bfb822dd2c25d';

        $this->assertSame($expected, $key);
    }

    public function testEmpties()
    {
        $key = Key::get(':', [null, '', 0, []]);

        $expected = 'cfcd208495d565ef66e7dff9f98764da';

        $this->assertSame($expected, $key);
    }

    public function testModelKey()
    {
        $key = Key::get(':', [User::class, 'foo', 'bar']);

        $expected = 'e07e8d069dbdfde3b73552938ec82f0a:acbd18db4cc2f85cedef654fccc4a4d8:37b51d194a7513e45b56f6524f2d51f2';

        $this->assertSame($expected, $key);
    }

    public function testCarbon()
    {
        $key = Key::get(':', [
            Carbon::parse('2022-10-07 14:00:00'),
            Carbon::parse('2022-10-07 15:00:00'),
        ]);

        $expected = '1391beb7fd700594df5a1d09d0afe677:72d97f016fa1dda7e212de874853ae28';

        $this->assertSame($expected, $key);
    }
}
