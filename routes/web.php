<?php

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

Route::get('/','HomeController@index');

Route::get('category/{name}','ListByCategoryController@getList')->name('category');

Route::get('insertCategories','ClassifiedController@insertCategories');

Route::get('insertSubCategories','ClassifiedController@insertSubCategories');
