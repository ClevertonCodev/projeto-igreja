<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->middleware('jwt.auth')->group(function(){
    Route::apiResource('estacas', 'App\Http\Controllers\EstacasController');
    Route::apiResource('alas', 'App\Http\Controllers\AlasController');
    Route::apiResource('users', 'App\Http\Controllers\UsersController');
    Route::apiResource('caravanas', 'App\Http\Controllers\CaravanasController');
    Route::get('me', 'App\Http\Controllers\AuthController@me');
    Route::apiResource('home', 'App\Http\Controllers\HomeController');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::apiResource('tiposV', 'App\Http\Controllers\TipoVeiculosController');
    Route::apiResource('veiculos', 'App\Http\Controllers\VeiculosController');
    Route::post('adicionarveiculos/{id}', 'App\Http\Controllers\CaravanasController@adicionarveiculos');
    Route::get('caravanasveiculos/{id}', 'App\Http\Controllers\CaravanasController@getone');
    Route::get('veiculoslivres/{id}','App\Http\Controllers\CaravanasController@caravanashasveiculoslivres');
});

Route::post('login', 'App\Http\Controllers\AuthController@login');



