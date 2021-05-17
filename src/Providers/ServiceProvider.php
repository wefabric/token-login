<?php

namespace Wefabric\TokenLogin\Providers;

use Illuminate\Support\Facades\Route;
use Wefabric\NovaCalendarTool\Http\Middleware\Authorize;
use Wefabric\TokenLogin\Console\Commands\CreateLoginTokens;
use Wefabric\TokenLogin\Console\Commands\DeleteExpiredLoginTokens;
use Wefabric\TokenLogin\Console\Commands\DeleteLoginTokens;
use Wefabric\TokenLogin\Console\Commands\RefreshLoginTokens;
use Wefabric\TokenLogin\Http\Middleware\TokenLoginEnabledMiddleware;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register()
    {
        parent::register();
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations')
        ], ['token-login', 'migrations']);

        $this->publishes([
            __DIR__.'/../../config/token-login.php' => config_path('token-login.php'),
        ], ['token-login', 'config']);

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        Route::middleware(config('token-login.middleware'))
            ->namespace('Wefabric\TokenLogin\Http\Controllers')
            ->group(__DIR__.'/../../routes/web.php');

        $this->commands([
            CreateLoginTokens::class,
            RefreshLoginTokens::class,
            DeleteLoginTokens::class,
            DeleteExpiredLoginTokens::class
        ]);
    }

}
