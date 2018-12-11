<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/12/10
 * Time: 上午9:23
 */

namespace App\Http\Controllers\Wechat\Gzh;

use App\Http\Controllers\BaseController;
use App\Services\Wechat\Gzh\GzhService;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GzhController extends BaseController
{
    /**
     * @var GzhService
     */
    protected $gzhService;

    /**
     * @desc
     */
    public function __construct(GzhService $service)
    {
        $this->gzhService = $service;
    }

    /**
     * @desc
     */
    public function index(Request $request)
    {
        $openId = $request->input('openid');
        try {
            $response = $this->gzhService->index();
        } catch (\Exception $e) {
            $response = [
                'err' => $e->getCode(),
                'msg' => $e->getMessage(),
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
