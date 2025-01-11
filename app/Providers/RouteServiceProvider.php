<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';
    protected $namespace = 'App\Http\Controllers';
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    // public function boot(): void
    // {
    //     $this->routes(function () {
    //         Route::prefix('api')
    //             ->middleware('api')
    //             ->group(base_path('routes/api.php'));

    //         Route::middleware('web')
    //             ->group(base_path('routes/web.php'));
    //     });
    // }
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapApiRoutes()
    {
        //dd('Chargement des routes API');
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

   

}
