## desc
* 可能是一个小程序后端

## env
* laravel 5.6
* php 7.2+
* 前端模板使用 [Laravel-admin](http://laravel-admin.org/docs)

## 运行
* 后台访问 `/admin`，登录后台的用户名和密码都是 `admin`
* 也可以使用 `php artisan route:list`，查看可以访问的路由
* `cp .env.example .env`
* `chmod -R 777 storage bootstrap/cache`
* `php artisan key:generate`
* laravel admin 相关 `php artisan admin:install`
* 下载编辑器相关的静态资源:
    - `wget https://github.com/wangfupeng1988/wangEditor/archive/v3.1.1.zip`，也可以下载最新的发布版本
    - `unzip v3.1.1.zip -d ./public/vendor/`
    - `mv public/vendor/wangEditor-3.1.1 public/vendor/wangEditor-3.0.9`

## 微信公众号
* 查看流程 http://lanewechat.lanecn.com/doc/main/aid-7
* 公众号的开发文档 https://www.easywechat.com/docs/master/overview
