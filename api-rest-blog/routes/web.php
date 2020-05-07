<?php

use Illuminate\Support\Facades\Route;

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

Route::resource('/api/customer', 'CustomerController');
Route::get('/prueba','PruebaController@prueba');


//especificas
Route::post('/api/user/upload','UserController@upload');
Route::get('/api/user/avatar/{filename}','UserController@avatar');
Route::post('/api/user/login','UserController@login');
Route::get('/api/user/getidentity','UserController@getIdentity');
//Route::get('/api/user/getidentity','UserController@getIdentity')->middleware(ApiAuthMiddleware::class);
//automaticas RESTful
Route::resource('/api/category', 'CategoryController',['except' => ['create','edit']]);
Route::resource('/api/user', 'UserController',['except' => ['create','edit']]);
Route::resource('/api/post','PostController',['except' => ['create','edit']]);
