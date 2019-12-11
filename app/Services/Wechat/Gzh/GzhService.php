<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/12/11
 * Time: 上午9:06
 */

namespace App\Services\Wechat\Gzh;

use App\Services\Wechat\Gzh\Services\WeatherService;
use EasyWeChat\Factory;
use App\Services\BaseService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class GzhService extends BaseService
{
    /**
     * @desc 回复粉丝消息
     * @routes
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
                // 处理事件，如关注、取关等
                if (strtolower($msg['MsgType']) === 'event') {
                    return $this->handleEvent($msg['Event']);
                }
                return $this->replyByUserContent($msg['Content']);
            });
            $response = $app->server->serve();
            return $response->send();
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
                $content = (new TextMenu)->showTextMenu();
                break;
            case 1:// 获取天气
                $service = new WeatherService();
                $service->getContent();
                $content = $service->render();
                break;
            default:
                $content = "你好，我已经收到消息...{$userContent}\n";
        }

        return $content;
    }

    /**
     * @desc 处理用户的事件
     */
    public function handleEvent($event='')
    {
        $string = '';
        switch ($event) {
            case 'subscribe':// 新用户关注
                $string = '客观您好，请往这边坐~';
                break;
            default:
        }

        return $string;
    }
}
