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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('students', ['uses' => 'StudentController@showAllStudents']);
    $router->get('students/{id}', ['uses' => 'StudentController@showOneStudent']);
    $router->post('students', ['uses' => 'StudentController@storeStudents']);
    $router->put('students/{id}', ['uses' => 'StudentController@updateStudent']);

    $router->get('users', ['uses' => 'UserController@showAllusers']);
    $router->get('users/{id}', ['uses' => 'UserController@showOneUser']);
    $router->post('users', ['uses' => 'UserController@storeUser']);
    $router->put('users/{id}', ['uses' => 'UserController@updateUser']);
});
