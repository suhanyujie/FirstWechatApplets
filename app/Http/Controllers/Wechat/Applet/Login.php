<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/5/22
 * Time: 下午7:06
 */

namespace App\Http\Controllers\Wechat\Applet;

use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use Illuminate\Http\Request;

class Login extends Controller
{
    public function index(Request $request)
    {
        // return [ $request->input('code') ];
        $config = [
            'app_id' => env('WECHAT_APP_ID',''),
            'secret' => env('WECHAT_SECRET',''),
            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => storage_path('wechat.log'),
            ],
        ];
        $app = Factory::miniProgram($config);
        $res = $app->getConfig();

        return ['test'];
    }

    public function test()
    {
        return [
            'status'=>1,
            'msg'=>'test',
        ];
    }
}
