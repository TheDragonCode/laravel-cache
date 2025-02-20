## Smart Cache for Laravel

<img src="https://preview.dragon-code.pro/the-dragon-code/smart-cache.svg?brand=laravel" alt="Laravel Cache"/>

[![Stable Version][badge_stable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![Github Workflow Status][badge_build]][link_build]
[![License][badge_license]][link_license]

## Installation

To get the latest version of `Smart Cache`, simply require the project using [Composer](https://getcomposer.org):

```bash
composer require dragon-code/laravel-cache
```

Or manually update `require` block of `composer.json` and run `composer update`.

```json
{
    "require": {
        "dragon-code/laravel-cache": "^4.0"
    }
}
```

## Upgrade Guide

Information on upgrade from version 3 to 4 is located in the [UPGRADE.md](UPGRADE.md) file. 

## Using

### Keys And Tags

In addition to passing an explicit value, you can also pass objects and arrays to the `keys` and `tags` methods.

For example:

```php
use DragonCode\Cache\Services\Cache;
use Tests\Fixtures\Dto\DtoObject;
use Tests\Fixtures\Simple\CustomObject;

$arr1 = ['foo', 'bar'];
$arr2 = new ArrayObject(['foo', 'bar']);
$arr3 = DtoObject::make(['foo' => 'Foo', 'bar'=> 'Bar']);
$arr4 = new CustomObject();

Cache::make()->key($arr1)->tags($arr1);
Cache::make()->key($arr2)->tags($arr3);
Cache::make()->key($arr2)->tags($arr3);
Cache::make()->key($arr4)->tags($arr4);

Cache::make()
    ->key([$arr1, $arr2, $arr3, $arr4, 'foo', 'bar'])
    ->tags([$arr1, $arr2, $arr3, $arr4, 'foo', 'bar']);
```

Unpacking and processing of objects occurs as follows:

```php
use DragonCode\Cache\Services\Cache;
use Tests\Fixtures\Dto\DtoObject;
use Tests\Fixtures\Simple\CustomObject;

['Foo', 'Bar'];
// as key: ['Foo', 'Bar']
// as tag: ['foo', 'bar']

new ArrayObject(['Foo', 'Bar']);
// as key: ['Foo', 'Bar']
// as tag: ['foo', 'bar']

DtoObject::make(['foo' => 'Foo', 'bar'=> 'Bar']);
// as key: ['Foo', 'Bar']
// as tag: ['foo', 'bar']

new CustomObject();
// as key: ['Foo']
// as tag: ['foo']
```

#### Keys Handling

Since the main problem of working with the cache's key compilation, this package solves it.

By passing values to the `keys` method, we get a ready-made key at the output.

The hash is formed by the value `key=value`, which allows avoiding collisions when passing identical objects.

In the case of passing nested arrays, the key is formed according to the principle `key1.key2=value`, where `key1`
and `key2` are the keys of each nested array.

For example:

```php
use DragonCode\Cache\Services\Cache;

Cache::make()->key('foo', 'bar', [null, 'baz', 'baq']);

// Key is `d76f2bde023f5602ae837d01f4ec1876:660a13c00e04c0d3ffb4dbf02a84a07a:6fc3659bd986e86534c6587caf5f431a:bd62cbee62e027d0be4b1656781edcbf`
```

This means that when writing to the cache, the tree view will be used.

For example:

```php
use DragonCode\Cache\Services\Cache;

Cache::make()->key('foo', 'foo')->put('Foo');
Cache::make()->key('foo', 'bar')->put('Bar');
Cache::make()->key('baz')->put('Baz');

// d76f2bde023f5602ae837d01f4ec1876:
//     086f76c144511e1198c29a261e87ca50: Foo
//     660a13c00e04c0d3ffb4dbf02a84a07a: Bar
// 1b9829f3bd21835a15735f3a65cc75e9: Baz
```

#### Disable key hashing

In some cases, you need to disable the use of the key hashing mechanism.
To do this, simply call the `hashKey(false)` method:

```php
use DragonCode\Cache\Services\Cache;

Cache::make()->key('foo', 'foo')->hashKey(false)->put('Foo');
Cache::make()->key('foo', 'bar')->hashKey(false)->put('Bar');
Cache::make()->key('baz')->hashKey(false)->put('Baz');

// 0=foo:
//     1=foo: Foo
//     1=bar: Bar
// 0=baz: Baz
```

```php
use DragonCode\Cache\Services\Cache;

Cache::make()->key([
            ['foo' => 'Foo'],
            ['bar' => 'Bar'],
            [['Baz', 'Qwerty']],
])->hashKey(false)->put('Baz');

// 0.foo=Foo:1.bar=Bar:2.0.0=Baz:2.0.1=Qwerty
```

### With Authentication

In some cases, it is necessary to bind the cache to certain users. To do this, we have added the `withAuth` helper.

```php
use DragonCode\Cache\Services\Cache;
use Illuminate\Support\Facades\Auth;

Cache::make()->withAuth()->key('foo', 'bar');

// instead of
Cache::make()->key(get_class(Auth::user()), Auth::id(), 'foo', 'bar');
```

When processing requests with a call to the withAuth method, the binding will be carried out not only by identifier, but
also by reference to the model class, since a project can
have several models with the possibility of authorization.

For example, `App\Models\Employee`, `App\Models\User`.

### When Enabled

#### Basic

```php
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()->key('foo', 'bar', ['baz', 'baq']);

$cache->put(static fn() => 'Some value');
// or
$cache->put('Some value');
// Contains cached `Some value`

$cache->remember(static fn() => 'Some value');
// or
$cache->remember('Some value');
// Contains cached `Some value`

$cache->rememberForever(static fn() => 'Some value');
// or
$cache->rememberForever('Some value');
// Contains cached `Some value`

$cache->get();
// Returns cached `Some value`

$cache->has();
// Returns `true`

$cache->doesntHave();
// Returns `false`

$cache->forget();
// Will remove the key from the cache.

$cache->flush();
// Clears keys or tags by value
```

```php
use DragonCode\Cache\Services\Cache;
use App\Models\User;

$user = User::first();

$cache = Cache::make()->key('foo');

$cache->put(static fn() => $user);
// or
$cache->put($user);
// Contains cached `$user`

$cache->remember(static fn() => $user);
// or
$cache->remember($user);
// Contains cached `$user`

$cache->rememberForever(static fn() => $user);
// or
$cache->rememberForever($user);
// Contains cached `$user`

$cache->get();
// Returns User model

$cache->has();
// Returns `true`

$cache->doesntHave();
// Returns `false`

$cache->forget();
// Will remove the key from the cache.

$cache->flush();
// Clears keys or tags by value
```

#### Method Call Chain

Sometimes in the process of working with a cache, it becomes necessary to call some code between certain actions, and in
this case the `call` method will come to the rescue:

```php
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()->key('foo');
$warmUp = false;

$cache
    ->call(fn (Cache $cache) => $cache->forget(), $warmUp)
    ->call(fn () => $someService->someMethod())
    ->remember('foo');
```

In addition, the `forget` method now returns an instance of the `Cache` object, so it can be used like this:

```php
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()->key('foo');

$cache
    ->forget()
    ->call(fn () => $someService->someMethod())
    ->remember('foo');
```

Previously, you had to use the following sequence:

```php
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()->key('foo');
$warmUp = false;

if ($warmUp) {
    $cache->forget();
}

$someService->someMethod()

$cache->remember('foo');
```

#### Custom TTL

By default, the cache will be written for 1 day.

The cache will be written for the specified number of minutes, seconds or the `DateTimeInterface` instance.

It does not matter in which direction the time shift will be. During processing, the value is converted to the `abs()`.

##### As Minutes

```php
use Carbon\Carbon;
use DateTime;
use DragonCode\Cache\Services\Cache;
use DragonCode\Cache\Support\Ttl;

Cache::make()->ttl(10);
Cache::make()->ttl('10');
Cache::make()->ttl(fn () => 10);

Cache::make()->ttl(Carbon::now()->addDay());
Cache::make()->ttl(new DateTime('tomorrow'));
```

##### As Seconds

```php
use Carbon\Carbon;
use DateTime;
use DragonCode\Cache\Services\Cache;

Cache::make()->ttl(10, false);
Cache::make()->ttl('10', false);
Cache::make()->ttl(fn () => 10, false);

Cache::make()->ttl(Carbon::now()->addDay(), false);
Cache::make()->ttl(new DateTime('tomorrow'), false);
```

##### By Objects And Custom Strings

You can also store all TTL values in one place - in the `config/cache.php` file.

To do this, add a `ttl` block to the file and [`define`](config/cache.php) a TTL for the objects.

After that you can use the following construction:

```php
use DragonCode\Cache\Services\Cache;
use Tests\Fixtures\Simple\CustomObject;

Cache::make()->ttl(CustomObject::class);
Cache::make()->ttl(new CustomObject());
Cache::make()->ttl('custom_key');
Cache::make()->ttl((object) ['foo' => 'Foo']);

// You can also specify that these values are in seconds, not minutes:
Cache::make()->ttl(CustomObject::class, false);
Cache::make()->ttl(new CustomObject(), false);
Cache::make()->ttl('custom_key', false);
Cache::make()->ttl((object) ['foo' => 'Foo'], false);
```

If the value is not found, the [default value](config/cache.php) will be taken, which you can also override in
the [configuration file](config/cache.php).

#### Tagged

For repositories that support tagging, the keys will be saved separated by tags.

```php
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()
    ->tags('actor', 'author')
    ->key('foo', 'bar', ['baz', 'baq']);

$cache->put(static fn() => 'Some value');
// or
$cache->put('Some value');
// Contains cached `Some value`

$cache->get();
// Returns cached `Some value`

$cache->has();
// Returns `true`

$cache->doesntHave();
// Returns `false`

$cache->forget();
// Will remove the key from the cache.

$cache->flush();
// Clears keys or tags by value
```

To retrieve a tagged cache item, pass the same ordered list of tags to the tags method and then call the get method with
the key you wish to retrieve:

```php
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()->key('foo', 'bar');

$cache->tags('actor', 'author')->put(static fn() => 'Some value');
// or
$cache->tags('actor', 'author')->put('Some value');
// Contains cached `Some value`

$cache->tags('actor', 'author')->get();
// Returns cached `Some value`

$cache->tags('actor')->get();
// Returns `null`

$cache->tags('author')->get();
// Returns `null`

$cache->tags('author')->flush();
// Clears keys or tags by value
```

> See the official Laravel [documentation](https://laravel.com/docs/cache#accessing-tagged-cache-items).

### When Disabled

Passing `when = false` will not write to the cache.

```php
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()
    ->when(false)
    ->key('foo', 'bar');

$value = $cache->put(static fn() => 'Some value');
// or
$value = $cache->put('Some value');
// Returns `Some value`

$cache->get();
// Returns `null`

$cache->has();
// Returns `false`

$cache->doesntHave();
// Returns `true`
```

You can also define whether to enable or disable the use of cache storage in the settings.

For example:

```php
// config/cache.php
return [
    'enabled' => [
        // App\Models\Page::class     => true,
        //
        // 'stdClass' => false,
        //
        // 'foo' => false,
    ],
];
```

```php
use App\Services\Some;use DragonCode\Cache\Services\Cache;

// as string
$cache = Cache::make()->when('foo');

// as class-string
$cache = Cache::make()->when(Some::class);
$cache = Cache::make()->when(static::class);
$cache = Cache::make()->when(self::class);

// as class
$cache = Cache::make()->when(new Some);
$cache = Cache::make()->when($this);

// as stdClass
$cache = Cache::make()->when((object)['foo' => 'Foo']);
```

## License

This package's licensed under the [MIT License](LICENSE).


[badge_build]:      https://img.shields.io/github/actions/workflow/status/TheDragonCode/laravel-cache/phpunit.yml?style=flat-square

[badge_downloads]:  https://img.shields.io/packagist/dt/dragon-code/laravel-cache.svg?style=flat-square

[badge_license]:    https://img.shields.io/github/license/TheDragonCode/laravel-cache.svg?style=flat-square

[badge_stable]:     https://img.shields.io/github/v/release/TheDragonCode/laravel-cache?label=stable&style=flat-square

[link_build]:       https://github.com/TheDragonCode/laravel-cache/actions

[link_license]:     LICENSE

[link_packagist]:   https://packagist.org/packages/dragon-code/laravel-cache
