<?php

namespace Jonathanwambua\Pesapal;

use Illuminate\Support\ServiceProvider;

class PesapalServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // publish the config file
        $configPath = __DIR__.'/config/pesapal.php';
        $this->publishes([$configPath => config_path('pesapal.php')], 'config');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}