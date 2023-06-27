## Smart Cache for Laravel

<img src="https://preview.dragon-code.pro/the-dragon-code/smart-cache.svg?brand=laravel" alt="Laravel Cache"/>

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
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
        "dragon-code/laravel-cache": "^3.7"
    }
}
```

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

For example:

```php
use DragonCode\Cache\Services\Cache;

Cache::make()->key('foo', 'bar', [null, 'baz', 'baq']);

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

### With Authentication

In some cases, it is necessary to bind the cache to certain users. To do this, we have added the `withAuth` helper.

```php
use DragonCode\Cache\Services\Cache;
use Illuminate\Support\Facades\Auth;

Cache::make()->withAuth()->key('foo', 'bar');

// instead of
Cache::make()->key(get_class(Auth::user()), Auth::id(), 'foo', 'bar');
```

When processing requests with a call to the withAuth method, the binding will be carried out not only by identifier, but also by reference to the model class, since a project can
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

$cache->get();
// Returns cached `Some value`

$cache->has();
// Returns `true`

$cache->doesntHave();
// Returns `false`

$cache->forget();
// Will remove the key from the cache.
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

$cache->get();
// Returns User model

$cache->has();
// Returns `true`

$cache->doesntHave();
// Returns `false`

$cache->forget();
// Will remove the key from the cache.
```

#### Method Call Chain

Sometimes in the process of working with a cache, it becomes necessary to call some code between certain actions, and in this case the `call` method will come to the rescue:

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

Cache::make()->ttl(Ttl::DAY);
Cache::make()->ttl(Ttl::WEEK);
Cache::make()->ttl(Ttl::MONTH);
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

If the value is not found, the [default value](config/cache.php) will be taken, which you can also override in the [configuration file](config/cache.php).

##### With Contract

Starting with version [`2.9.0`](https://github.com/TheDragonCode/laravel-cache/releases/tag/v2.9.0), we added the ability to dynamically specify TTLs in objects. To do this, you
need to implement the `DragonCode\Contracts\Cache\Ttl` contract into your object and add a method that returns one of the following types of variables: `DateTimeInterface`
, `Carbon\Carbon`, `string`
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

Cache::make()->ttl(new Foo('foo'));
// TTL is 7380 seconds

Cache::make()->ttl(new Foo('bar'));
// TTL is 27360 seconds

Cache::make()->ttl(new Foo('foo'), false);
// TTL is 123 seconds

Cache::make()->ttl(new Foo('bar'), false);
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

[badge_unstable]:   https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[link_build]:       https://github.com/TheDragonCode/laravel-cache/actions

[link_license]:     LICENSE

[link_packagist]:   https://packagist.org/packages/dragon-code/laravel-cache
