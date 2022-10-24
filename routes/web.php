<?php

use App\Http\Controllers\StudentController;
use Laudis\Neo4j\ClientBuilder;
use Illuminate\Support\Facades\Route;
use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\Contracts\TransactionInterface;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('store', [StudentController::class, 'store'])->name('store');

Route::get('test', function(){

    $client = ClientBuilder::create()
    ->withDriver('bolt', 'bolt+s://neo4j:students@localhost') // creates a bolt driver
    ->withDriver('https', 'https://test.com', Authenticate::basic('user', 'password')) // creates an http driver
    ->withDriver('neo4j', 'neo4j://neo4j.test.com?database=students', Authenticate::oidc('token')) // creates an auto routed driver with an OpenID Connect token
    ->withDefaultDriver('bolt')
    ->build();

//    dd($client);

$result = $client->writeTransaction(static function (TransactionInterface $tsx) {
    $result = $tsx->run('MERGE (x {y: "z"}:X) return x');
    return $result->first()->get('x')['y'];
});

echo $result; // echos 'z'


});
