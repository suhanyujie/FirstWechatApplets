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
        try{
            $app = Factory::officialAccount($config);
            $user = $app->user;
            $app->server->push(function ($msg)use($user) {
                $fromUser = $user->get($msg['FromUserName']);
                $msgJson = json_encode([$user,$msg], true);
                Log::useFiles(storage_path().'/logs/laravel.log')->info("用户注册原始数据:{$msgJson}");
                switch ($msg) {
                    default:
                        return "你好，{$fromUser}，我已经收到消息\n";
                }
            });
            $accessToken = $app->access_token;
            $token = $accessToken->getToken();
            $response = $app->server->serve();
            $response->send();
        }catch (\Exception $e) {
            return [
                'err'=>$e->getCode(),
                'msg'=>$e->getMessage(),
            ];
        }

        return $response;
    }


    /**
     * @desc
     */
    public function oauthCallback()
    {
        $config = config('wechat.gzh');
        $app = Factory::officialAccount($config);
        $user = $app->oauth->user();
        session(['wechat_oauth_user'=>$user->toArray()]);
        $targetUrl = session()->has('target_url') ? session('target_url') : '/';

        return redirect()->to($targetUrl);
    }
}
