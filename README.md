## Laravel Cache

<img src="https://preview.dragon-code.pro/TheDragonCode/laravel-cache.svg?brand=laravel" alt="Laravel Cache"/>

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]

## Installation

To get the latest version of `Laravel Cache`, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require dragon-code/laravel-cache
```

Or manually update `require` block of `composer.json` and run `composer update`.

```json
{
    "require": {
        "dragon-code/laravel-cache": "^2.0"
    }
}
```

## Using

### Keys And Tags

In addition to passing an explicit value, you can also pass objects and arrays to the `keys` and `tags` methods.

For example:

```php
use DragonCode\Cache\Services\Cache;
use DragonCode\SimpleDataTransferObject\DataTransferObject;
use Tests\Fixtures\Simple\CustomObject;

$arr1 = ['foo', 'bar'];
$arr2 = new ArrayObject(['foo', 'bar']);
$arr3 = DataTransferObject::make(['foo', 'bar']);
$arr4 = new CustomObject();

Cache::make()->key($arr1)->tags($arr1);
Cache::make()->key($arr2)->tags($arr3);
Cache::make()->key($arr2)->tags($arr3);
Cache::make()->key($arr4)->tags($arr4);

Cache::make()->key([$arr1, $arr2, $arr3, $arr4, 'foo', 'bar'])->tags([$arr1, $arr2, $arr3, $arr4, 'foo', 'bar']);
Cache::make()->key([$arr1, $arr2, $arr3, $arr4, 'foo', 'bar'])->tags([$arr1, $arr2, $arr3, $arr4, 'foo', 'bar']);
Cache::make()->key([$arr1, $arr2, $arr3, $arr4, 'foo', 'bar'])->tags([$arr1, $arr2, $arr3, $arr4, 'foo', 'bar']);
```

#### Keys Handling

Since the main problem of working with the cache's key compilation, this package solves it.

By passing values to the `keys` method, we get a ready-made key at the output.

For example:

```php
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()->key('foo', 'bar', [null, 'baz', 'baq']);

// Key is `acbd18db4cc2f85cedef654fccc4a4d8:37b51d194a7513e45b56f6524f2d51f2:73feffa4b7f6bb68e44cf984c85f6e88:b47951d522316fdd8811b23fc9c2f583`
```

This means that when writing to the cache, the tree view will be used.

For example:

```php
use DragonCode\Cache\Services\Cache;

Cache::make()->key('foo', 'foo')->put('foo');
Cache::make()->key('foo', 'bar')->put('bar');
Cache::make()->key('baz')->put('baz');

// acbd18db4cc2f85cedef654fccc4a4d8:
//     acbd18db4cc2f85cedef654fccc4a4d8: foo
//     37b51d194a7513e45b56f6524f2d51f2: bar
// 73feffa4b7f6bb68e44cf984c85f6e88: baz
```

### When Enabled

#### Basic

By default, the cache will be written for 1 day.

```php
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()->key('foo', 'bar', ['baz', 'baq']);

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
```

#### Custom TTL

The cache will be written for the specified number of minutes, seconds or the `DateTimeInterface` instance.

It does not matter in which direction the time shift will be. During processing, the value is converted to the `abs()`.

##### As Minutes

```php
use Carbon\Carbon;
use DateTime;
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()->ttl(10);

$cache = Cache::make()->ttl('10');

$cache = Cache::make()->ttl(fn () => 10);

$cache = Cache::make()->ttl(Carbon::now()->addDay());

$cache = Cache::make()->ttl(new DateTime('tomorrow'));
```

##### As Seconds

```php
use Carbon\Carbon;
use DateTime;
use DragonCode\Cache\Services\Cache;

$cache = Cache::make()->ttl(10, false);

$cache = Cache::make()->ttl('10', false);

$cache = Cache::make()->ttl(fn () => 10, false);

$cache = Cache::make()->ttl(Carbon::now()->addDay(), false);

$cache = Cache::make()->ttl(new DateTime('tomorrow'), false);
```

##### By Objects And Custom Strings

You can also store all TTL values in one place - in the `config/cache.php` file.

To do this, add a `ttl` block to the file and [`define`](config/cache.php) a TTL for the objects.

After that you can use the following construction:

```php
use DragonCode\Cache\Services\Cache;
use Tests\Fixtures\Simple\CustomObject;

$cache = Cache::make()->ttl(CustomObject::class);
$cache = Cache::make()->ttl(new CustomObject());
$cache = Cache::make()->ttl('custom_key');

// You can also specify that these values are in seconds, not minutes:
$cache = Cache::make()->ttl(CustomObject::class, false);
$cache = Cache::make()->ttl(new CustomObject(), false);
$cache = Cache::make()->ttl('custom_key', false);
```

If the value is not found, the [default value](config/cache.php) will be taken, which you can also override in the [configuration file](config/cache.php).

##### With Contract

Starting with version [`2.9.1`](https://github.com/TheDragonCode/laravel-cache/releases/tag/v2.9.0), we added the ability to dynamically specify TTLs in objects. To do this, you
need to implement the Foo contract into your object and add a method that returns one of the following types of variables: `DateTimeInterface`, `Carbon\Carbon`, `string`
or `integer`.

This method will allow you to dynamically specify the TTL depending on the code being executed.

For example:

```php
use DragonCode\Cache\Services\Cache;
use DragonCode\Contracts\Cache\Ttl;

class Foo implements Ttl
{
    protected $value;
    
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function cacheTtl(): int
    {
        return $this->value === 'foo' ? 123 : 456;
    }
}

$cache = Cache::make()->ttl(new Foo('foo'));
// TTL is 7380 seconds

$cache = Cache::make()->ttl(new Foo('bar'));
// TTL is 27360 seconds

$cache = Cache::make()->ttl(new Foo('foo'), false);
// TTL is 123 seconds

$cache = Cache::make()->ttl(new Foo('bar'), false);
// TTL is 456 seconds
```

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
```

To retrieve a tagged cache item, pass the same ordered list of tags to the tags method and then call the get method with the key you wish to retrieve:

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

## License

This package's licensed under the [MIT License](LICENSE).


[badge_downloads]:  https://img.shields.io/packagist/dt/dragon-code/laravel-cache.svg?style=flat-square

[badge_license]:    https://img.shields.io/github/license/TheDragonCode/laravel-cache.svg?style=flat-square

[badge_stable]:     https://img.shields.io/github/v/release/TheDragonCode/laravel-cache?label=stable&style=flat-square

[badge_unstable]:   https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[link_license]:     LICENSE

[link_packagist]:   https://packagist.org/packages/dragon-code/laravel-cache
