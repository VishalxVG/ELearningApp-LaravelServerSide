<?php

use App\Http\Controllers\Api\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Group of routes that has the same namespace(defined in routesServices.php)

Route::group(['namespace'=>'Api'], function(){
    //Route::post('/login',[UserController::class, 'createUser']);
    
    //* Since we are using gloabl namespace for our apis in routeServices file
    //* the way to write out api is different
    Route::post('/login','UserController@createUser');
    
    // A middleWare to have extra protective layer , such that not eveyone can call the api
    Route::group(['middleware'=>['auth:sanctum']],function(){
        Route::any('/courseList' , [CourseController::class , 'courseList']);
    });
});


//Route::post('/auth/login', [UserController::class , 'loginUser']);

