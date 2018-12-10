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

class GzhController extends BaseController
{

    /**
     * @desc
     */
    public function index()
    {
        $config = [
            'app_id'        => 'wx42999873a1dbdfc2',
            'secret'        => 'uta3RavVyrCwa5WIpSI2FFwh5f6m9vchAEQPC2ibD2e',
            'token'         => 'weixin',
            //...
        ];
        $app = Factory::officialAccount($config);
        $app->server->push(function ($msg) {
            switch ($msg) {
                default:
                    return "收到消息\n";
            }
        });
        $response = $app->server->serve();


        return $response->send();
    }
}
