<?php

declare(strict_types=1);

namespace Tests;

use DragonCode\Cache\ServiceProvider;
use DragonCode\Cache\Services\Cache;
use Orchestra\Testbench\TestCase as BaseTestCase;
use stdClass;
use Tests\Concerns\RefreshCache;
use Tests\Concerns\Userable;
use Tests\Fixtures\Simple\CustomObject;
use Tests\Fixtures\Simple\DragonCodeArrayable;

abstract class TestCase extends BaseTestCase
{
    use RefreshCache;
    use Userable;

    protected string $cache = 'array';

    protected int $ttl = 60;

    protected mixed $when;

    protected array $tags = ['qwerty', 'cache'];

    protected array $keys = ['Foo', 'Bar', 'Baz'];

    protected mixed $value = 'Foo';

    protected string $value_second = 'Bar';

    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->setConfig($app);
    }

    protected function setConfig($app): void
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $app['config'];

        $config->set('cache.default', $this->cache);

        $config->set('cache.ttl', [
            CustomObject::class        => 300,
            DragonCodeArrayable::class => 400,

            stdClass::class => 600,

            'custom' => 600,
        ]);
    }

    protected function cache(array $tags = [], ?array $keys = null): Cache
    {
        $tags = $tags ?: $this->tags;
        $keys = $keys ?: $this->keys;

        return Cache::make()
            ->when($this->when)
            ->ttl($this->ttl)
            ->key(...$keys)
            ->tags(...$tags);
    }
}
