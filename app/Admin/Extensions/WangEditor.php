<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/10/14
 * Time: 下午2:53
 */

namespace App\Admin\Extensions;


use Encore\Admin\Form\Field;

class WangEditor extends Field
{
    protected $view = 'admin.wang-editor';

    protected static $css = [
        '/vendor/wangEditor-3.0.9/release/wangEditor.min.css',
    ];

    protected static $js = [
        '/vendor/wangEditor-3.0.9/release/wangEditor.min.js',
    ];

    public function render()
    {
        $name = $this->formatName($this->column);
        $token = csrf_token();

        $this->script = <<<EOT

var E = window.wangEditor
var editor = new E('#{$this->id}');
editor.customConfig.zIndex = 0
//editor.customConfig.uploadImgShowBase64 = true
editor.customConfig.uploadImgServer = '/api/file'
editor.customConfig.uploadFileName = 'file'
// 将图片大小限制为 3M
editor.customConfig.uploadImgMaxSize = 1 * 1024 * 1024
// 限制一次最多上传 5 张图片
editor.customConfig.uploadImgMaxLength = 5
// 将 timeout 时间改为 5s
editor.customConfig.uploadImgTimeout = 5000
editor.customConfig.uploadImgParams = {
    // 如果版本 <=v3.1.0 ，属性值会自动进行 encode ，此处无需 encode
    token: '{$token}'
}
editor.customConfig.onchange = function (html) {
    $('input[name=\'$name\']').val(html);
}
editor.create()

EOT;
        return parent::render();
    }
}
