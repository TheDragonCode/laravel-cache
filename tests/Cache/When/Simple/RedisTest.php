<?php

declare(strict_types=1);

namespace Tests\Cache\When\Simple;

use DragonCode\Cache\Services\Cache;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Cache\When\Base;
use Tests\Fixtures\Models\User;

class RedisTest extends Base
{
    protected string $cache = 'redis';

    public function testGet()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put($this->value);

        $this->assertSame($this->value, $this->cache()->get());
        $this->assertSame($this->value, $this->cache(['qwerty', 'cache'])->get());

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testPut()
    {
        $this->assertSame($this->value, $this->cache()->put($this->value));

        $this->assertSame($this->value, $this->cache()->get());
        $this->assertSame($this->value, $this->cache(['qwerty', 'cache'])->get());

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    #[DataProvider('booleanData')]
    public function testFlexible(bool $isTrue)
    {
        $interval = $isTrue
            ? $this->positiveTtlInterval
            : $this->negativeTtlInterval;

        $this->assertSame($this->value, $this->cache()->flexible($interval)->remember($this->value));

        $this->assertSame($this->value, $this->cache()->get());
        $this->assertSame($this->value, $this->cache(['qwerty', 'cache'])->get());

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testRemember()
    {
        $this->assertSame($this->value, $this->cache()->remember($this->value));

        $this->assertSame($this->value, $this->cache()->get());
        $this->assertSame($this->value, $this->cache(['qwerty', 'cache'])->get());

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testRememberForever()
    {
        $this->assertSame($this->value, $this->cache()->rememberForever($this->value));

        $this->assertSame($this->value, $this->cache()->get());
        $this->assertSame($this->value, $this->cache(['qwerty', 'cache'])->get());

        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testForget()
    {
        $this->assertNull($this->cache()->get());

        $this->cache()->put($this->value);

        $this->cache()->forget();

        $this->assertNull($this->cache()->get());
        $this->assertNull($this->cache(['qwerty', 'cache'])->get());
        $this->assertNull($this->cache(['qwerty'])->get());
        $this->assertNull($this->cache(['cache'])->get());
    }

    public function testFlushByKeys()
    {
        $cache1 = Cache::make()->key('foo');
        $cache2 = Cache::make()->key('bar');

        $this->assertNull($cache1->get());
        $this->assertNull($cache2->get());

        $cache1->put('qwe');
        $cache2->put('rty');

        $this->assertSame('qwe', $cache1->get());
        $this->assertSame('rty', $cache2->get());

        $cache1->flush();

        $this->assertNull($cache1->get());
        $this->assertNull($cache2->get());
    }

    public function testFlushByTags()
    {
        $tags1 = Cache::make()->tags('some1');
        $tags2 = Cache::make()->tags('some2');

        $cache1 = (clone $tags1)->key('foo');
        $cache2 = (clone $tags2)->key('bar');

        $this->assertNull($cache1->get());
        $this->assertNull($cache2->get());

        $cache1->put('qwe');
        $cache2->put('rty');

        $this->assertSame('qwe', $cache1->get());
        $this->assertSame('rty', $cache2->get());

        $tags1->flush();

        $this->assertNull($cache1->get());
        $this->assertSame('rty', $cache2->get());
    }

    public function testHas()
    {
        $this->assertFalse($this->cache()->has());

        $this->cache()->put($this->value);

        $this->assertTrue($this->cache()->has());
        $this->assertTrue($this->cache(['qwerty', 'cache'])->has());

        $this->assertFalse($this->cache(['qwerty'])->has());
        $this->assertFalse($this->cache(['cache'])->has());
    }

    public function testDoesntHave()
    {
        $this->assertTrue($this->cache()->doesntHave());

        $this->cache()->put($this->value);

        $this->assertFalse($this->cache()->doesntHave());
        $this->assertFalse($this->cache(['qwerty', 'cache'])->doesntHave());

        $this->assertTrue($this->cache(['qwerty'])->doesntHave());
        $this->assertTrue($this->cache(['cache'])->doesntHave());
    }

    public function testWithAuth()
    {
        $user = new User([
            'id'   => 123,
            'name' => 'John Doe',
        ]);

        Auth::setUser($user);

        $this->assertTrue(Auth::check());

        $this->assertTrue($this->cache()->doesntHave());
        $this->assertTrue($this->cache()->withAuth()->doesntHave());

        $this->cache()->withAuth()->put($this->value_second);

        $this->assertTrue($this->cache()->doesntHave());
        $this->assertTrue($this->cache()->withAuth()->has());

        $key = [User::class, $this->user_id, 'Foo', 'Bar', 'Baz'];

        $this->assertSame($this->value_second, $this->cache()->withAuth()->get());
        $this->assertNull($this->cache([], $key)->get());
    }
}
