<?php

use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Support\Facades\Route;

// Rutas Específicas
Route::post('/api/product/upload','ProductController@upload')->name('product.upload');
Route::get('/api/product/getImage/{filename}','ProductController@getImage')->name('product.getImage');
Route::post('/api/user/login','UserController@login')->name('user.login');
Route::get('/api/user/getidentity','UserController@getIdentity')->name('user.getIdentity');
//Otra forma:
//Route::get('/api/user/getidentity','UserController@getIdentity')->middleware(ApiAuthMiddleware::class);

// Rutas Automáticas RESTful
Route::resource('/api/category', 'CategoryController',['except' => ['create','edit']]);
Route::resource('/api/customer', 'CustomerController',['except' => ['create','edit']]);
Route::resource('/api/product', 'ProductController',['except' => ['create','edit']]);
Route::resource('/api/sale_product', 'Sale_ProductController',['except' => ['create','edit']]);
Route::resource('/api/sale', 'SaleController',['except' => ['create','edit']]);
Route::resource('/api/supplier', 'SupplierController',['except' => ['create','edit']]);
Route::resource('/api/user', 'UserController',['except' => ['create','edit']]);
