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

Route::resource('church','churchController',['except' => ['create', 'edit']]);
Route::resource('address','addressController',['except' => ['create', 'edit']]);
Route::resource('role','roleController',['except' => ['create', 'edit']]);
Route::resource('class_type','classtypeController',['except' => ['create', 'edit']]);
