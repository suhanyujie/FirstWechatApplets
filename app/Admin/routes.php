<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');


    //自定义
    //新增文章
    $router->get('/article/create', 'Article\ArticleController@create');
    $router->post('/article/create', 'Article\ArticleController@create');
    $router->get('/article/detail/{id}', 'Article\ArticleController@detail');
    $router->any('/article/edit/{id}', 'Article\ArticleController@edit');

});
