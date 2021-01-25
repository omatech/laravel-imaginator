<?php

namespace Omatech\Imaginator\Providers;

use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function register()
    {
        $this->helpers();
    }

    private function helpers()
    {
        require __DIR__.'/../helpers/ImaginatorGenUrls.php';
    }
}
