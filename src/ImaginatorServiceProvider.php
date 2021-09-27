<?php

namespace Omatech\Imaginator;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Omatech\Imaginator\Contracts\GetImageInterface;
use Omatech\Imaginator\Middlewares\GlideSecurityMiddleware;
use Omatech\Imaginator\Providers\ConfigurationServiceProvider;

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
        Blade::component('imaginator', ImaginatorComponent::class);
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
    }

    public function provides()
    {
        return ['imaginator'];
    }
}
