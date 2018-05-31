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

class Login extends Controller
{
    public function index()
    {
        $config = [
            'app_id' => 'wx958a25f9890f4fec',
            'secret' => '5f898e0f376baab3e5bb7a05f717c0cc',
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
        return $res;
    }
}
