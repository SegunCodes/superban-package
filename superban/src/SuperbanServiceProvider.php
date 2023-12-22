<?php

namespace Superban;

use Illuminate\Support\ServiceProvider;
use Superban\Middleware\SuperbanMiddleware;

class SuperbanServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/superban.php' => config_path('superban.php'),
        ], 'superban-config');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/superban.php', 'superban');

        // Register the Superban middleware
        $this->app->singleton(SuperbanMiddleware::class, function ($app) {
            return new SuperbanMiddleware(
                $app['cache'],
                config('superban.cache_driver'),
                $app['config']['cache.stores'][$app['config']['cache.default']]['driver']
            );
        });
    }
}
