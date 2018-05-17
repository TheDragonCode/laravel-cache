## Cache Modification helper

### IMPORTANT!
#### The package is no longer supported. To implement caching, use the [GeneaLabs/laravel-model-caching](https://github.com/GeneaLabs/laravel-model-caching) package.


Cache helper for the [Illuminate Cache](https://github.com/illuminate/cache) package.

![cache](https://user-images.githubusercontent.com/10347617/40197417-1209115a-5a1c-11e8-83b3-02bad51c6e88.png)

<p align="center">
    <a href="https://styleci.io/repos/119809288"><img src="https://styleci.io/repos/119809288/shield" alt="StyleCI" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/cache"><img src="https://img.shields.io/packagist/dt/andrey-helldar/cache.svg?style=flat-square" alt="Total Downloads" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/cache"><img src="https://poser.pugx.org/andrey-helldar/cache/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/cache"><img src="https://poser.pugx.org/andrey-helldar/cache/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
    <a href="LICENSE"><img src="https://poser.pugx.org/andrey-helldar/cache/license?format=flat-square" alt="License" /></a>
</p>


## Installation

To get the latest version of Cache Modification, simply require the project using [Composer](https://getcomposer.org/):

```bash
$ composer require andrey-helldar/cache
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "andrey-helldar/cache": "^1.0"
    }
}
```

Once installed, you need to register the `Helldar\Cache\ServiceProvider::class` service provider in your `config/app.php`, or if you're using Laravel 5.5, this can be done via the automatic package discovery.


## How to use

```php
return cache_mod()
    ->key('it', 'is', 'a', 'key')
    ->minutes(60)
    ->tags('it', 'is', 'a', 'tags')
    ->remember(function() {
        return 'my value';
    });

return cache_mod()
    ->key('it', 'is', 'a', 'key')
    ->remember(function() {
        return 'my value';
    });
```

To disable the caching, you need to set the `CACHE_DRIVER=array` in `.env`.


## Copyright and License

Cache Modification for [Illuminate Cache](https://github.com/illuminate/cache) package was written by Andrey Helldar for the Laravel framework 5.4 or above, and is released under the MIT License. See the [LICENSE](LICENSE) file for details.


## Translation

Translations of text and comment by Google Translate. Help with translation +1 in karma :)
