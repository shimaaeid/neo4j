<?php

namespace App\Providers;

use App\Repository\StudentRepoClass;
use App\Repository\StudentRepoClassNeo4j;
use App\Repository\StudentRepoInterface;
use App\Repository\StudentRepoNeo4jInterface;
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
        $this->app->bind(
            StudentRepoInterface::class, function($app){
                return new StudentRepoClass();
            }
        );

        $this->app->bind(
            StudentRepoNeo4jInterface::class, function($app){
                return new StudentRepoClassNeo4j();
            }
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
