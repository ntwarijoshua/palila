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



$router->post('/register', 'UserController@registerEventUser');
$router->post('/get-token', 'UserController@authenticateUser');

$router->group(['middleware' => 'auth:api'], function ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });
});
