<?php

namespace App\Providers;

use App\Repositories\FileRepository;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            FileRepositoryInterface::class,
            FileRepository::class,
        );
    }
}
