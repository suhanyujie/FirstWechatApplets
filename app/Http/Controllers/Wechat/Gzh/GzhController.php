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
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GzhController extends BaseController
{
    /**
     * @desc
     */
    public function index(Request $request)
    {
        $openId = $request->input('openid');
        $config = config('wechat.gzh');
        try{
            $app = Factory::officialAccount($config);
            $user = $app->user;
            file_put_contents(storage_path('logs/wechat1.log'),"$openId\n");

            $app->server->push(function ($msg) use ($user) {
                return "您好！欢迎关注我!";
//                $fromUser = $user->get($msg['FromUserName']);
//                $msgJson  = json_encode([$user, $msg], true);
//                Log::useFiles(storage_path() . '/logs/laravel.log')->info("用户注册原始数据:{$msgJson}");
//                switch ($msg) {
//                    default:
//                        return "你好，{$fromUser}，我已经收到消息\n";
//                }
            });
            $response = $app->server->serve();
//            $message = new Text('hello world!');
//            $app->customer_service->message($message)->to($openId)->send();
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
