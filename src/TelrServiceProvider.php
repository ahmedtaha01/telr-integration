<?php

namespace AhmedTaha\Telr;

use Illuminate\Support\ServiceProvider;

class TelrServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/telr.php',
            'telr'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/telr.php' => config_path('telr.php'),
        ], 'telr-config');
    }
}