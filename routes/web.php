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

Route::get('/', function () {
    return view('welcome');
});

// 文章区块的路由
Route::group(['namespace'=>'Article'],function(){
    Route::any('/article/index', 'ArticleController@index');
    Route::get('/article/{id}', 'ArticleController@detail');
});


