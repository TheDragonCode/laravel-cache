<?php

declare(strict_types=1);

namespace DragonCode\Cache;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/cache.php' => $this->app->configPath('cache.php'),
        ], 'config');
    }
}
