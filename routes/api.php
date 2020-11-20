<?php

use Illuminate\Http\Request;

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

Route::get('anuncios', 'ClassifiedController@listAds');

Route::get('insertSubCategories','ClassifiedController@insertSubCategories');
Route::get('filterByCategory/{category_id}', 'ClassifiedController@filterByCategory');
Route::get('filterByTitle/{title}', 'ClassifiedController@filterByTitle');
Route::get('filterByDescription/{description}', 'ClassifiedController@filterByDescription');
