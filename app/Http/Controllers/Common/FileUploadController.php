<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/10/14
 * Time: 下午4:29
 */

namespace App\Http\Controllers\Common;


use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Services\Common\File\FileService;

class FileUploadController extends BaseController
{
    /**
     * @desc 文件上传
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $result = FileService::store([
            'file'     => $request->file('file'),
            'typeCode' => 1,
        ]);
        $outputResult = [
            'errno' => $result['status'] == 1 ? 0 : $result['status'],
            'data'  => [
                $result['data'],
            ],
        ];

        return response()->json($outputResult);
    }
}
