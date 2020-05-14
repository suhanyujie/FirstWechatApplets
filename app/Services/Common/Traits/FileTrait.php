<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/10/14
 * Time: 下午4:33
 */

namespace App\Services\Common\Traits;


trait FileTrait
{
    protected $zywOssApiData = [];

    /**
     * @desc 上传前的一些参数初始化工作
     */
    public function uploadInit()
    {
        $this->zywOssApiData['ossParam'] = config('fileUpload.ZYW_JAVA_OSS_API_CONF');
        $this->zywOssApiData['appEnv'] = defined('APP_ENV') ? APP_ENV : '110';
        if (!empty($this->zywOssApiData['ossParam']['forceUploadApiEnv'])) {
            $this->zywOssApiData['appEnv'] = $this->zywOssApiData['ossParam']['forceUploadApiEnv'];
        }
    }

    /**
     * @desc 上传的前期准备以及上传，只适用于表单形式的文件上传
     *
     * @paramArr array 传入$_FILES中的一个单元 例如$_FILES['file']
     * @param array $paramArr
     * @return array
     */
    public function uploadWrap($paramArr=[])
    {
        $options = [
            'file'         => '',// $_FILES中的一个单元，例如$_FILES['file']
            'objectForced' => '',//强制设定的object 值为uri+文件名，例如：public/2018/a.txt
            'isPic'        => 1,//1是图片 0不是
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        if (empty($objectForced)){
            return ['status'=>401, 'message'=>'参数缺省objectForced'];
        }
        $this->uploadInit();
        //上传一个文件
        try {
            $result = $this->ossUploadFile([
                'file'         => $file['tmp_name'],
                'isPic'        => $options['isPic'],
                'fileType'     => $file['type'],
                'fileSize'     => $file['size'],
                'objectForced' => $objectForced,
                'uploadType'   => 1,//1本地文件 2web文件
            ]);
            // 如果失败，则重试，并且做多重试5次
            if ($result['status'] != 1) {
                for ($tryTimes = 1; $tryTimes <= 5; $tryTimes++) {
                    $result = $this->ossUploadFile([
                        'file'         => $file['tmp_name'],
                        'isPic'        => $options['isPic'],
                        'fileType'     => $file['type'],
                        'fileSize'     => $file['size'],
                        'objectForced' => $objectForced,
                        'uploadType'   => 1,//1本地文件 2web文件
                    ]);
                    if ($result['status'] == 1) {
                        break;
                    }
                    sleep(0.3);
                }
                $result['message'] .= '重试次数为：'.$tryTimes.'！';
            }

        } catch (\Exception $e) {
            $result = [
                'status'  => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }

        return $result;
    }

    /**
     * @desc 本地单个文件的上传
     * @param $paramArr
     * @return array [
     *      'status'=>'',//为1表示成功  其他表示异常
     *      'message'=>'',//异常时的描述信息
     * ]
     */
    public function uploadOneFileWraper($paramArr)
    {
        $options = [
            'filePath'     => '',//需要上传的文件的绝对路径
            'objectForced' => '',//强制使用指定的文件名
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        $file = $this->getFileInfo($filePath);
        $this->uploadInit();
        //上传一个文件
        try {
            $result = $this->ossUploadFile([
                'file'         => $file['tmp_name'],
                'fileType'     => $file['type'],
                'fileSize'     => $file['size'],
                'objectForced' => $objectForced,
                'uploadType'   => 1,//1本地文件 2web文件
            ]);
        } catch (\Exception $e) {
            $result = [
                'status'  => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }

        return $result;
    }

    /**
     * @desc 获取文件的信息，后缀，文件名，尺寸
     * @param string $filePath
     * @return array $fileInfo = [
     *      'tmp_name'=>'',//文件的绝对路径
     *      'type'=>'',//类型，后缀
     *      'size'=>'',//大小
     * ];
     */
    protected function getFileInfo($filePath='')
    {
        if (!$filePath)return [];
        $arr = pathinfo($filePath);
        $fileInfo = [
            'tmp_name' => $filePath,
            'type'     => $arr['extension'],
            'size'     => filesize($filePath),
        ];
        return $fileInfo;
    }

    /**
     * @desc 图片的上传
     * @param array $paramArr
     * @return array
     * @throws \Exception
     * @author suhy
     * @date 2018年5月2日
     */
    public function ossUploadFile($paramArr=[])
    {
        $options = [
            'file'         => '',// file url
            'isPic'        => 1,
            'objectForced' => '',// 上传后，可以用于访问的URI，例：oss-php-sdk-test/upload-test-object-name.txt
            'uploadType'   => 1,//1本地文件 2web文件。为1时，file值必须为文件的绝对路径。值为2时，file值必须为文件url
            'fileType'     => '',
            'fileSize'     => '',
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        if (!$options['file']) {
            return ['status'=>2, 'message'=>'参数缺省file'];
        }
        if (!defined('APP_ENV')) {
            return ['status'=>21, 'message'=>'没有定义环境变量 APP_ENV ！'];
        }
        $fileContent = file_get_contents($file);
        $fileContent = base64_encode($fileContent);
        //图片文件需要前缀
        $this->zywOssApiData['fileTypeArr'] = $fileExtArr = explode('/',$fileType);
        $fileExt = $fileExtArr[1];
        if (!in_array($fileExt, ['xlsx', 'docx'])) {
            $fileContent = $this->getFileBase64PrevString($fileExt).$fileContent;
        }
        $configParam = $this->zywOssApiData['ossParam'];
        //检查文件是否合法
        $this->checkFile([
            'fileSize' => $fileSize,//
            'fileType' => $fileType,
        ]);
        $header = [
            'Content-Type' => 'application/json',
        ];
        $isRename = empty($objectForced) ? '2' : '1';
        if ($options['isPic'] == 1) {
            $filePath = $objectForced;
        } else {
            $filePath = explode('/', $objectForced);
            $filePath = $filePath[0];
        }
        $extensionParam = json_encode([
            'fileKey' => $objectForced,
        ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $postData = [
            'content'           => $fileContent,
            'fileType'          => $fileExt,
            'projectName'       => $configParam['paramProjectName'],
            'isSynchronization' => 1,
            'isPic'             => $options['isPic'],//
            'filePath'          => $filePath,
            'isRename'          => $isRename,
            'extension'         => $extensionParam,
        ];
        $appEnv = $this->zywOssApiData['appEnv'];
        $url = $configParam['urls'][$appEnv];
        $httpClient = $this->getHttpClient();
        try {
            $response = $httpClient->request('POST', $url, [
                'headers' => $header,
                'body'    => json_encode($postData,JSON_UNESCAPED_SLASHES),
            ]);
        } catch (\Exception $e) {
            return ['status' => 404, 'message' => '接口请求错误，原因：' . $e->getMessage()];
        }
        $responseStr = (string)$response->getBody();
        $result = json_decode($responseStr, true);
        if (isset($result['status']) && $result['status']==-999) {
            return ['status' => 401, 'message' => '请求失败：' . $result['message']];
        }
        if ($result['err'] != 0 || !isset($result['res']['path'])) {
            return ['status'=>411, 'message'=>'上传不成功，图片接口异常：'.$result['msg'],];
        }

        return ['status'=>1, 'data'=>$result['res']['path'], 'message'=>'上传成功！'];
    }

    /**
     * 生成GetObject的签名url,主要用于私有权限下的读访问控制
     * 在调用之前需要确定 已经调用过uploadInit方法！！！
     *
     * @param array $paramArr
     * @return array
     */
    function getSignedUrlForGettingObject($paramArr=[])
    {
        $options = [
            'filePath' => '',//图片的object，例如 oss-php-sdk-test/upload-test-object-name.txt
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        if (!$filePath)return ['status'=>4, 'message'=>'参数缺省filePath'];
        $configParam = $this->zywOssApiData['ossParam'];
        $header = [
            'Content-Type'=>'application/json',
        ];
        //获取下载地址，无需重命名  1表示重命名  2表示不重命名
        $isRename = 2;
        $fileExt = $this->getFileExt($filePath);
        $postData = [
            'content'           => 'down',
            'fileType'          => $fileExt,
            'projectName'       => $configParam['paramProjectName'],
            'isSynchronization' => 1,
            'isPic'             => 1,//
            'filePath'          => $filePath,
            'isRename'          => $isRename,
            'deadTime'          => $configParam['signedUrlExpire'],
        ];
        $appEnv = $this->zywOssApiData['appEnv'];
        $url = $configParam['downloadUrls'][$appEnv];
        $httpClient = $this->getHttpClient();
        try {
            $response = $httpClient->request('POST', $url, [
                'headers' => $header,
                'body'    => json_encode($postData,JSON_UNESCAPED_SLASHES),
            ]);
        } catch (\Exception $e) {
            return ['status' => 404, 'message' => '接口请求错误，原因：' . $e->getMessage()];
        }
        $responseStr = (string)$response->getBody();
        $result = json_decode($responseStr, true);
        if (isset($result['err']) && (int)$result['err'] == 0) {
            $data = [
                'signedUrl'=>$result['res']['path'],
            ];
            return ['status' => 1, 'data' => $data,];
        } else {
            return ['status' => 423, 'message' => $result['message'],];
        }
    }

    /**
     * @desc 获取文件的后缀名
     * @param string $filePath
     * @return mixed
     */
    protected function getFileExt($filePath='')
    {
        $fileInfo = pathinfo($filePath);

        return $fileInfo['extension'];
    }

    /**
     * @desc 保存并水印图片
     * @param string $webImageUrl http://asset-privacy.oss-cn-shanghai.aliyuncs.com/201865/db94fdec60204f11bbe572277b08d10a.png?OSSAccessKeyId=xxx&Expires=1528268822&Signature=Vx5VP%2B2sdWMJSVbJ3ARmB0Y7lsc%3D
     * @return array [
     *      'status'=>1,//处理的状态 1正确  其他异常
     *      'data'=>'',//正常时，返回的信息，在这里是图片的uri
     * ]
     */
    protected function uploadWebImage($paramArr=[])
    {
        $options = [
            'webImageUrl'  => '',//
            'objectForced' => '',
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        //将图片存放在/tmp的目录下
        $fileInfoArr = explode('?', $webImageUrl);
        $imageFileInfo = pathinfo($fileInfoArr[0]);
        $baseName = $imageFileInfo['basename'];
        $tmpImag = file_get_contents($webImageUrl);
        $tmpFilePath = $this->zywOssApiData['ossParam']['tmpFileMiddlePath'].'/'.$baseName;
        touch($tmpFilePath);
        file_put_contents($tmpFilePath,$tmpImag);
        //将带有水印的图片上传到阿里云
        $uploadResult = $this->uploadOneFileWraper([
            'filePath'     => $tmpFilePath,// 文件的绝对路径
            'objectForced' => $objectForced,
        ]);
        //如果失败，则重试，并且做多重试5次
        if ($uploadResult['status'] != 1 && strpos($uploadResult['message'],'上传异常')!==false) {
            for ($tryTimes = 1;$tryTimes<=3;$tryTimes++) {
                //将带有水印的图片上传到阿里云
                $uploadResult = $this->uploadOneFileWraper([
                    'filePath'     => $tmpFilePath,// 文件的绝对路径
                    'objectForced' => $objectForced,
                ]);
                if ($uploadResult['status'] == 1) {
                    break;
                }
                sleep(0.3);
            }
            $uploadResult['message'] .= '重试次数为：'.$tryTimes.'！';
        }
        if ($uploadResult['status'] != 1) {
            return ['status' => 3, 'error' => $uploadResult['message']];
        } else {
            return ['status' => 1, 'data' => $uploadResult['data'],];
        }
    }

    /**
     * @desc 对文件的大小、类型进行检测
     * @throws \Exception
     */
    protected function checkFile($paramArr=[])
    {
        $options = [
            'fileSize' => '',//
            'fileType' => '',// 文件的mime类型 例如"application/pdf"
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        $fileExt = $this->zywOssApiData['fileTypeArr'][1];
        //超过设定的大小，则抛异常
        switch ($fileExt) {
            case 'pdf':
                //pdf文件的大小限制是10M
                $limitSize = 10 * 1024 * 1024;
                break;
            case 'docx':
            case 'xlsx'://xlsx文件大小的限制是5M
                //pdf文件的大小限制是10M
                $limitSize = 5 * 1024 * 1024;
                break;
            default:
                $limitSize = $this->zywOssApiData['ossParam']['fileSizeLimit'];
        }
        if ($fileSize > $limitSize) {
            throw new \Exception('file size must less than ' . $limitSize . ' bytes!', '411');
        }
        //文件类型
        $allowFileType = $this->zywOssApiData['ossParam']['allowFileType'];
        $allowFileType = explode(',', $allowFileType);
        if (!in_array($fileExt, $allowFileType)) {
            throw new \Exception('file type '. $fileExt . ' is not allow!','412');
        }
    }

    /**
     * @desc 获取图片的base64的前缀部分
     */
    protected function getFileBase64PrevString($imageType='png')
    {
        // 支持上传的文件mime类型
        $imageMimeArr = [
            'bmp'  => 'image/bmp',
            'gif'  => 'image/gif',
            'png'  => 'image/png',
            'jpg'  => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'pdf'  => 'application/pdf',
            'xlsx' => 'application/xlsx',
            'docx' => 'application/docx',
        ];
        if (!isset($imageMimeArr[$imageType])) {
            throw new \Exception('the file mimetype is now allow!', 415);
        }

        return 'data:'.$imageMimeArr[$imageType].';base64,';
    }

    public function getHttpClient()
    {
        if(isset($this->zywOssApiData['httpClient']))return $this->zywOssApiData['httpClient'];
        $this->zywOssApiData['httpClient'] = new HttpClient([
            'base_uri' => '',
            'timeout'  => 30,
        ]);

        return $this->zywOssApiData['httpClient'];
    }
}