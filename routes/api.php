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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () 
{
    Route::post('login', 'UserController@login');
    Route::post('signup', 'UserController@signup');
  
    Route::group(['middleware' => 'auth:api'], function() 
    {
        Route::get('logout', 'UserController@logout');
        Route::get('/user', 'UserController@user');
    });
});


//Password reset api
Route::group([    
    'namespace' => 'Auth',    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});


//Access points
Route::post('store','AccessController@store');
Route::get('fetch','AccessController@fetch');