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
Route::middleware([])->group(function () {
    Route::post('/file', 'Common\FileUploadController@upload');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([])->group(function () {
    Route::get('/applet1/list', 'Wechat\Applet\Login@index');
    Route::get('/applet1/test', 'Wechat\Applet\Login@test');
});

