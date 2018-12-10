<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/12/10
 * Time: 上午9:23
 */

namespace App\Http\Controllers\Wechat\Gzh;

use App\Http\Controllers\BaseController;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\Log;

class GzhController extends BaseController
{
    /**
     * @desc
     */
    public function index()
    {
        $config = config('wechat.gzh');
        $app = Factory::officialAccount($config);
        $app->server->push(function ($msg) {
            $msgJson = json_encode($msg, true);
            Log::useFiles(storage_path().'/logs/laravel.log')->info("用户注册原始数据:{$msgJson}");
            switch ($msg) {
                default:
                    return "收到消息\n";
            }
        });
        $response = $app->server->serve();
        $response->send();

        return $response;
    }
}
