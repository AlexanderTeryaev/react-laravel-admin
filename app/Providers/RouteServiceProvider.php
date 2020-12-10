<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        Route::pattern('id', '[0-9]+');
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapAdminApiRoutes();
        $this->mapSpaRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->name('admin_api.')
            ->namespace($this->namespace. '\AdminApiControllers')
            ->group(base_path('routes/admin_api.php'));
    }

    /**
     * Define the "spa" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapSpaRoutes()
    {
        Route::middleware('spa')
            ->namespace($this->namespace)
            ->group(base_path('routes/spa.php'));
    }
}
