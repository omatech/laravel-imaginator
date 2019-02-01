<?php

namespace Omatech\Imaginator;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Omatech\Imaginator\Contracts\GetImageInterface;
use Omatech\Imaginator\Providers\HelpersServiceProvider;
use Omatech\Imaginator\Providers\ConfigurationServiceProvider;
use Omatech\Imaginator\Middlewares\GlideSecurityMiddleware;

class ImaginatorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->providers();

        $this->loadViewsFrom(__DIR__.'/views', 'imaginator');
        Blade::component('imaginator::imaginator', 'imaginator');

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->app->bind(GetImageInterface::class, config('imaginator.get_image_class'));
    }

    /**
     * Register the application service providers.
     *
     * @return void
     */
    public function register()
    {
        $this->app['router']->aliasMiddleware('glideSecurity', GlideSecurityMiddleware::class);
    }

    private function providers()
    {
        $this->app->register(ConfigurationServiceProvider::class);
        $this->app->register(HelpersServiceProvider::class);
    }

    public function provides()
    {
        return ['imaginator'];
    }
}
