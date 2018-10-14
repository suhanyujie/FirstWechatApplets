<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/10/14
 * Time: 下午4:31
 */

namespace App\Services\Common\File;


use App\Services\BaseService;
use App\Services\Common\Traits\FileTrait as FileTraitTrait;
use Illuminate\Support\Facades\Storage;

class FileService extends BaseService
{
    use FileTraitTrait;

    public function __construct()
    {
        $this->uploadInit();
    }

    /**
     * 如果文件存在 则跳转到真实文件地址，如果没有则报404
     * @param array $paramArr
     * @return \Illuminate\Http\JsonResponse
     */
    public function readFile($paramArr=[])
    {
        $options = [
            'filePath' => '',//
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        if (empty($filePath)) {
            abort(404);
        }
        $signResult = $this->getSignedUrlForGettingObject([
            'filePath'=>$options['filePath'],
        ]);
        if ($signResult['status'] != 1) {
            return response()->json($signResult);
        }

        header('Location:'.$signResult['data']['signedUrl']);
    }

    /**
     * 上传pdf文件
     * @param array $paramArr [
     *      'file'=>'',//从request中去除的文件对象
     *      'typeCode'=>'',//1图片 2代表pdf文件
     * ]
     * @return array
     */
    public function zywStoreFile($paramArr=[])
    {
        $options = [
            'file'         => '',//
            'typeCode'     => 2,// 1.图片;2.pdf文件 4.excel文件
            'objectForced' => '',
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        if (!($file instanceof UploadedFile)) {
            return ['status'=>2, 'message'=>'参数错误，文件对象参数有误！'];
        }
        // 文件是否上传成功
        if ($file->isValid()) {
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $type = $file->getClientMimeType();     // image/jpeg
            $fileInfoArr = [
                'tmp_name' => $file->path(),
                'type'     => $file->getClientMimeType(),
                'size'     => $file->getClientSize(),
            ];
            if (strlen($fileInfoArr['type']) > 20) {
                $infoArr = pathinfo($fileInfoArr['tmp_name']);
                $type = $fileInfoArr['type'] = 'application/' . $infoArr['extension'];
            }
            $checkResult = self::checkFileTypeAllow([
                'typeCode' => $typeCode,
                'type'     => $type,
            ]);
            if ($checkResult['status'] != 1) {
                return ['status'=>21,'message'=>$checkResult['message']];
            }
            // 上传文件
            $str = str_replace('.','',microtime(true).uniqid());
            if (!empty($options['objectForced'])) {
                $filename = $options['objectForced'];
            } else {
                $filename = date('Y-m-d') . '/' . date('YmdHis') . '-' . $str . '.' . $ext;
            }
            if ($options['typeCode'] == 1) {
                $fileType = 1;
            } else {
                $fileType = 3;
            }
            $result = $this->uploadWrap([
                'isPic'        => $fileType,//1是图片 0不是
                'file'         => $fileInfoArr,// $_FILES中的一个单元，例如$_FILES['file']
                'objectForced' => $filename,//强制设定的object 值为uri+文件名，例如：public/2018/a.txt
            ]);

            return $result;
        } else {
            return ['status'=>10000,'message'=>'文件不合法！'.$file->getClientMimeType(),'data'=>''];
        }
    }

    /**
     * Create an UploadedFile object from absolute path
     *
     * @static
     * @param     string $path
     * @param     bool $public default false
     * @return    object(Symfony\Component\HttpFoundation\File\UploadedFile)
     * @author    Alexandre Thebaldi
     */
    public function pathToUploadedFile($path, $public = false)
    {
        $name = IlluminateFile::name($path);
        $extension = IlluminateFile::extension($path);
        $originalName = $name . '.' . $extension;
        $mimeType = IlluminateFile::mimeType($path);
        $size = IlluminateFile::size($path);
        $error = null;
        $test = $public;
        $object = new UploadedFile($path, $originalName, $mimeType, $size, $error, $test);
        return $object;
    }

    /**
     * 上传图片
     * @param array $paramArr [
     *      'file'=>'',//从request中去除的文件对象
     *      'typeCode'=>'',//1图片 2代表pdf文件
     * ]
     * @return array
     */

    public function zywStoreImage($paramArr=[])
    {
        $options = [
            'file'     => '',//
            'typeCode' => 1,// 图片
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        if (!($file instanceof UploadedFile)) {
            return ['status'=>2, 'message'=>'参数错误，文件对象参数有误！'];
        }
        // 文件是否上传成功
        if ($file->isValid()) {
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $type = $file->getClientMimeType();     // image/jpeg
            $checkResult = self::checkFileTypeAllow([
                'typeCode' => $typeCode,
                'type'     => $type,
            ]);
            if ($checkResult['status'] != 1) {
                return ['status'=>21,'message'=>$checkResult['message']];
            }
            // 上传文件
            $str = str_replace('.','',microtime(true).uniqid());
            $filename = date('Y-m-d') . '/' . date('YmdHis') . '-' . $str . '.' . $ext;
            $fileInfoArr = [
                'tmp_name' => $file->path(),
                'type'     => $file->getClientMimeType(),
                'size'     => $file->getClientSize(),
            ];
            $result = $this->uploadWrap([
                'file'         => $fileInfoArr,// $_FILES中的一个单元，例如$_FILES['file']
                'objectForced' => $filename,//强制设定的object 值为uri+文件名，例如：public/2018/a.txt
            ]);
            return $result;
        }else{
            return ['status'=>6200,'message'=>'文件不合法！'];
        }
    }

    /**
     * @desc 文件的存储
     * @param array $paramArr
     * @return array
     */
    public static function store($paramArr=[])
    {
        $options = [
            'file'     => '',//
            'typeCode' => 1,// 图片
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        $file = $options['file'];
        if (!is_object($file)) {
            return ['status'=>2, 'message'=>'参数错误，文件对象参数有误！'];
        }
        // 文件是否上传成功
        if ($file->isValid()) {
            // 获取文件相关信息
            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg
            $checkResult = self::checkFileTypeAllow([
                'typeCode' => $typeCode,
                'type'     => $type,
            ]);
            if ($checkResult['status'] != 1) {
                return ['status'=>21,'message'=>$checkResult['message']];
            }
            // 检查大小
            $result = self::checkSize($file);
            if ($result['status']!=1) {
                return $result;
            }
            // 上传文件
            $str = str_replace('.','',microtime(true).uniqid());
            $filename = date('Ymd') . '/' . date('YmdHis') . '-' . $str . '.' . $ext;
            // 使用我们新建的uploads本地存储空间（目录）
            $bool = Storage::disk('local')->put($filename, file_get_contents($realPath));
            if (!$bool) {
                return ['status'=>21,'message'=>'失败，文件存储异常！'];
            }
            $configUrl = config('filesystems.disks.public.url');
            $fileUrl = $configUrl . DIRECTORY_SEPARATOR . $filename;

            return ['status' => 1, 'data' => $fileUrl];
        } else {
            return ['status' => 3, 'message' => '文件不合法'];
        }
    }

    //检查图片的大小
    public static function checkSize($file)
    {
        $size = $file->getSize();
        // 1MB
        if ($size > 1*1024*1024) {
            return ['status'=>2, 'message'=>'文件不能大于1MB'];
        }

        return ['status'=>1, 'message'=>'文件大小合格'];
    }

    /**
     * @desc 检查对应的文件类型是否合法
     * @param array $paramArr
     * @return array
     */
    public static function checkFileTypeAllow($paramArr=[])
    {
        $options = [
            'typeCode' => '',// 文件类型的代码 按照下面switch定义的
            'type'     => '',// 资源的类型名
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        switch ($typeCode) {
            case 1:// 图片
                if (!in_array($type, ['image/png', 'image/jpg', 'image/jpeg',])) {
                    return ['status' => 3, 'message' => '请上传正确的图片文件'];
                }
                break;
            case 2:// pdf
                if ($type != 'application/pdf') {
                    return ['status' => 31, 'message' => '请上传正确的pdf文件'];
                }
                break;
            case 4://excel office文件
                if (!in_array($options['type'], ['application/xlsx',
                    'application/docx',])) {
                    return ['status' => 26600, 'message' => '请上传正确的excel-office文件'];
                }
                break;
            default:
                return ['status' => 4, 'message' => '文件类型未知'];
        }

        return ['status'=>1, 'message'=>'文件检查通过，类型合法'];
    }
}
