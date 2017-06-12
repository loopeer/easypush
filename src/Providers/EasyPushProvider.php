<?php

namespace Loopeer\EasyPush\Providers;

use Illuminate\Support\ServiceProvider;

class EasyPushProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/easypush.php' => config_path('easypush.php'),
        ], 'easypush');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
