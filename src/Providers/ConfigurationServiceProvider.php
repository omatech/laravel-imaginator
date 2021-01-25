<?php

namespace Omatech\Imaginator\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigurationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publish();
    }

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function register()
    {
        $this->configuration();
    }

    /**
     * Publish configurations.
     *
     * @return void
     */
    private function publish()
    {
        $this->publishes([
            __DIR__.'/../config/imaginator.php' => config_path('imaginator.php'),
        ]);
    }

    /**
     * Register configurations.
     *
     * @return void
     */
    private function configuration()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/imaginator.php',
            'imaginator'
        );
    }
}
