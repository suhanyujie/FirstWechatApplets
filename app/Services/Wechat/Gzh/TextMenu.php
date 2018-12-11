<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/12/11
 * Time: 上午9:44
 */

namespace App\Services\Wechat\Gzh;

/**
 * 显示菜单以及一些文字信息
 * Class TextMenu
 * @package App\Services\Wechat\Gzh
 */
class TextMenu
{
    const TEXT_MENU = <<<MENU
您好，欢迎使用。可以按数字回复，我能提供的功能如下：
    0-显示菜单
    1-查询今天的天气
MENU;

    /**
     * @desc 显示菜单
     * @return string
     */
    public function showTextMenu()
    {
        return self::TEXT_MENU;
    }
}
