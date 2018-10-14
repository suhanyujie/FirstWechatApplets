<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/10/14
 * Time: 下午12:13
 */

namespace App\Http\Controllers\Article;

use App\Http\Controllers\BaseController;
use App\Services\Article\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends BaseController
{
    /**
     * @var ArticleService
     */
    protected $articleService;

    public function __construct(ArticleService $service)
    {
        $this->articleService = $service;
    }

    public function index(Request $request)
    {
        $input   = $request->input();
        $param   = [
            'offset' => 0,
            'limit'  => 100,
        ];
        $dataArr = $this->articleService->getList($param);
        $output  = [
            'articles' => $dataArr,
        ];
        if ($request->isMethod('post')) {
            $returnArr = [
                'status'  => 1,
                'message' => '获取数据成功！',
                'data'    => $dataArr,
            ];
            return response()->json($returnArr);
        }
        if (isset($input['debug']) && $input['debug'] == 1) {
            return view('article.indexVue', $output);
        }

        return view('article.index', $output);
    }

    /**
     * 展示文章详情
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request, $id)
    {
        $input       = $request->input();
        $input['id'] = $id;
        $result      = $this->articleService->detail($input);
        if ($result['status'] != 1) {
            abort(404);
        }
        $output = [
            'article' => $result['data'],
        ];

        return view('article.detail', $output);
    }
}
