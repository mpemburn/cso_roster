<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ServiceServiceProvider
 * @package App\Providers
 */
class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(app_path().'/Services/*.php') as $filename){
            require_once($filename);
        }
    }
}
