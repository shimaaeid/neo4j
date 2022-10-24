<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class Neo4jServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        // $this->app->singleton(Session::class, function($app){
        //     return $this->getClient();
        // });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if(config('database.connections.neo4j.password_testing')
        === config('database.connections.neo4j.password')){
            abort('5oo', 'the passwords for the Neo4j Testing and the live couldnot the same');
        }
    }

    // protected function getClient(){

    // }
}
