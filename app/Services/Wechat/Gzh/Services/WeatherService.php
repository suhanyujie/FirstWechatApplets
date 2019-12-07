<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2019-12-07
 * Time: 19:00
 */

namespace App\Services\Wechat\Gzh\Services;

/**
 * 获取天气服务
 * Class WeatherService
 * @package App\Services\Wechat\Gzh\Services
 */
class WeatherService
{
    /**@var array*/
    protected $content = '';

    /**
     * @desc 获取内容
     */
    public function getContent()
    {
        $url = 'https://www.tianqiapi.com/api';
        $appId = env('WECHAT_GZH_SERVICE_WEATHER_USERID', '');
        $secret = env('WECHAT_GZH_SERVICE_WEATHER_USERID', '');
        $url = $url."/?version=v1&cityid=101020100&appid={$appId}&appsecret={$secret}";
        $resJson = file_get_contents($url);
        $resArr = json_decode($resJson, true);
        $this->content = $resArr;
    }

    /**
     * @desc 渲染输出的内容
     */
    public function render()
    {
        $resArr = $this->content;
        $cityName = $resArr['city'];
        $weather = $resArr['data'][0]['wea'];

        return "$cityName -> {$weather}";
    }
}
