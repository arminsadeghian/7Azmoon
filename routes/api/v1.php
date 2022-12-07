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
        $router->get('', 'API\V1\UsersController@index');
        $router->post('', 'API\V1\UsersController@store');
        $router->put('', 'API\V1\UsersController@updateInformation');
        $router->put('change-password', 'API\V1\UsersController@updatePassword');
        $router->delete('', 'API\V1\UsersController@delete');
    });

    $router->group(['prefix' => 'categories/'], function () use ($router) {
        $router->get('', 'API\V1\CategoriesController@index');
        $router->post('', 'API\V1\CategoriesController@store');
        $router->put('', 'API\V1\CategoriesController@update');
        $router->delete('', 'API\V1\CategoriesController@delete');
    });

    $router->group(['prefix' => 'quizzes/'], function () use ($router) {
        $router->get('', 'API\V1\QuizzesController@index');
        $router->post('', 'API\V1\QuizzesController@store');
        $router->put('', 'API\V1\QuizzesController@update');
        $router->delete('', 'API\V1\QuizzesController@delete');
    });

    $router->group(['prefix' => 'questions/'], function () use ($router) {
        $router->get('', 'API\V1\QuestionsController@index');
        $router->post('', 'API\V1\QuestionsController@store');
        $router->put('', 'API\V1\QuestionsController@update');
        $router->delete('', 'API\V1\QuestionsController@delete');
    });

    $router->group(['prefix' => 'answer-sheets/'], function () use ($router) {
        $router->get('', 'API\V1\AnswerSheetsController@index');
        $router->post('', 'API\V1\AnswerSheetsController@store');
        $router->delete('', 'API\V1\AnswerSheetsController@delete');
    });

});
