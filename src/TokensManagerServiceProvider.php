<?php

namespace Lachezargrigorov\TokensManager;

use Illuminate\Support\ServiceProvider;

class TokensManagerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //publish the config if needed
        //php artisan vendor:publish --provider="Lachezargrigorov\TokensManager\TokensManagerServiceProvider" --tag="config"
        $this->publishes( [
            __DIR__ . '/../config/tokens_manager.php' => config_path( 'tokens_manager.php' ),
        ], 'config' );

        //publish the migrations if needed
        //php artisan vendor:publish --provider="Lachezargrigorov\TokensManager\TokensManagerServiceProvider" --tag="migrations"
        $this->publishes( [
            __DIR__ . '/../database/migrations/2017_03_15_083618_create_tokens_manager_table.php' => database_path( 'migrations/2017_03_15_083618_create_tokens_manager_table.php' ),
        ], 'migrations' );
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__ . '/../config/tokens_manager.php', 'tokens_manager' );

        $this->app->bind('tokens-manager',function($app)
        {
            return new TokensManager($app);
        });
    }
}