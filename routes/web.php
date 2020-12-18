<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
	return $router->app->version();
});

$router->get('rs_covid', ['uses' => 'RsController@all']);
$router->get('rs_covid/{id}', ['uses' => 'RsController@byId']);
$router->get('rs_covid_haversine', ['uses' => 'RsController@getNearbyRs']);
$router->post('rs_covid', ['uses' => 'RsController@store']);
$router->post('rs_covid/edit/{id}', ['uses' => 'RsController@update']);
$router->delete('rs_covid/{id}', ['uses' => 'RsController@destroy']);

$router->get('news', ['uses' => 'NewsController@all']);
$router->get('news/{id}', ['uses' => 'NewsController@byId']);
$router->post('news', ['uses' => 'NewsController@store']);
$router->post('news/edit/{id}', ['uses' => 'NewsController@update']);
$router->delete('news/{id}', ['uses' => 'NewsController@destroy']);

$router->post('login', ['uses' => 'AdminController@login']);
$router->post('register', ['uses' => 'AdminController@store']);


// $router->get('/key', function() {
// 	return \Illuminate\Support\Str::random(32);
// });
