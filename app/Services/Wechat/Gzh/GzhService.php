<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/12/11
 * Time: 上午9:06
 */

namespace App\Services\Wechat\Gzh;

use EasyWeChat\Factory;
use App\Services\BaseService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class GzhService extends BaseService
{
    /**
     * @desc 回复粉丝消息
     * @return Response
     */
    public function index()
    {
        $config = config('wechat.gzh');
        try{
            $app = Factory::officialAccount($config);
            $user = $app->user;
            $app->server->push(function ($msg) use ($user) {
                $msgStr = json_encode($msg,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
                //$fromUser = $user->get($msg['FromUserName']);
                $msgJson  = json_encode([$user, $msg], true);
                Log::info("原始数据:{$msgJson}。");
                return $this->replyByUserContent($msg['Content']);
            });
            $response = $app->server->serve();
            $response->send();
        }catch (\Exception $e) {
            $response = [
                'status'=>$e->getCode(),
                'msg'=>$e->getMessage(),
            ];
        }

        return $response;
    }

    /**
     * @desc 通过用户输入的内容判断要返回的内容信息
     * @return string
     */
    public function replyByUserContent($userContent='')
    {
        switch ($userContent) {
            case 0:
                return (new TextMenu)->showTextMenu();
                break;
            default:
                return "你好，我已经收到消息...{$userContent}\n";
        }
    }
}
