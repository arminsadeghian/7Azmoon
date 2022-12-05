<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api/v1/'], function () use ($router) {

    $router->group(['prefix' => 'users/'], function () use ($router) {
        $router->post('', 'API\V1\UsersController@store');
        $router->put('', 'API\V1\UsersController@updateInformation');
        $router->put('change-password', 'API\V1\UsersController@updatePassword');
        $router->delete('', 'API\V1\UsersController@delete');
        $router->get('', 'API\V1\UsersController@index');
    });

    $router->group(['prefix' => 'categories/'], function () use ($router) {
        $router->post('', 'API\V1\CategoriesController@store');
        $router->delete('', 'API\V1\CategoriesController@delete');
        $router->put('', 'API\V1\CategoriesController@update');
    });

});
