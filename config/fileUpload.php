<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/10/14
 * Time: 下午4:34
 */
return [

    //java upload api java上传图片接口参数配置
    'ZYW_JAVA_OSS_API_CONF' => [
        'urls'              => [
            'local' => 'http://192.168.40.101:9120/file/privatefileUpload',//本地开发
            '115'   => 'http://test.api.51zhaoyou.com/basic_img/file/privatefileUpload',//开发机
            '116'   => 'http://java-test.api.51zhaoyou.com/basic_img/file/privatefileUpload',//测试机
            '110'   => 'http://pre.api.51zhaoyou.com/basic_img/file/privatefileUpload',//预发
            '128'   => 'http://api.51zhaoyou.com/basic_img/file/privatefileUpload',//线上
        ],
        'downloadUrls'      => [
            'local' => 'http://192.168.40.101:9120/file/privatefileDownload',//本地开发
            '115'   => 'http://test.api.51zhaoyou.com/basic_img/file/privatefileDownload',//开发机
            '116'   => 'http://java-test.api.51zhaoyou.com/basic_img/file/privatefileDownload',//测试机
            '110'   => 'http://pre.api.51zhaoyou.com/basic_img/file/privatefileDownload',//预发
            '128'   => 'http://api.51zhaoyou.com/basic_img/file/privatefileDownload',//线上
        ],
        'paramProjectName'  => 'front',//上传时的调用来源例如front,group
        'apiType'           => 1,//1同步 2异步，截止20180601，目前不支持异步
        'fileSizeLimit'     => 1024 * 1024,//文件大小限制，单位byte
        'allowFileType'     => 'png,jpg,jpeg,gif,bmp,pdf,xlsx,docx',
        'signedUrlExpire'   => 3600*2,
        'tmpFileMiddlePath' => '/tmp',
        'forceUploadApiEnv' => '128',
    ],
];
