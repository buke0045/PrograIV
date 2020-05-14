<?php

use Illuminate\Support\Facades\Route;

//especificas
Route::post('/api/user/upload','UserController@upload')->name('user.upload');
Route::get('/api/user/avatar/{filename}','UserController@avatar')->name('user.avatar');
Route::post('/api/user/login','UserController@login')->name('user.login');
Route::get('/api/user/getidentity','UserController@getIdentity')->name('user.getIdentity');
//Route::get('/api/user/getidentity','UserController@getIdentity')->middleware(ApiAuthMiddleware::class);
//automaticas RESTful
Route::resource('/api/category', 'CategoryController');
Route::resource('/api/customer', 'CustomerController');
Route::resource('/api/product','ProductController');
Route::resource('/api/sale_product','Sale_ProductController');
Route::resource('/api/sale','SaleController');
Route::resource('/api/supplier','SupplierController');
Route::resource('/api/user', 'UserController',['except' => ['create','edit']]);
