<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/10/14
 * Time: 下午1:05
 */

namespace App\Admin\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Article\ArticleArticleModel;
use App\Services\Article\ArticleService;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\ModelForm;

class ArticleController extends Controller
{
    use ModelForm;

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
        return Admin::Grid(ArticleArticleModel::class, function (Grid $grid) use ($request) {
            $grid->id('ID')->sortable();
            $grid->title('标题')->display(function ($title) use ($grid) {
                return '<a href="/article/' . $this->id . '" target="_blank">' . $title . '</a>';
            });
            $grid->add_time();
            $grid->update_time();

            $grid->perPages([10, 30, 50]);
        });
    }

    /**
     * 展示文章详情
     * @param Request $request
     * @return Content
     */
    public function detail(Request $request)
    {
        return Admin::content(function (Content $content){
            $content->header('详情展示');
            $content->description('展示给客户查看的详细信息');
            $content->body('123123123');
        });
    }

    /**
     * @desc 编辑文章
     * @param Request $request
     * @param $id
     * @return Content
     */
    public function edit(Request $request,$id)
    {
        return Admin::content(function (Content $content)use($request,$id){
            $input = $request->input();
            $content->header('编辑内容');
            $content->description('编辑和修改详细信息');
            if ($request->isMethod('put')) {
                $service = new ArticleService();
                $input['id'] = $id;
                $result = $service->edit($input);
                if ($result['status'] == 1) {
                    return redirect('/admin/article/edit/'.$id);
                }
                return response()->json($result);
            }
            $bodyStr = $this->form('/admin/article/edit/'.$id)->edit($id);
            $content->body($bodyStr);
        });
    }

    /**
     * @desc 创建文章
     * @param Request $request
     * @return Content
     */
    public function create(Request $request)
    {
        return Admin::content(function (Content $content)use($request){
            $input = $request->input();
            $content->header('新增文章');
            $content->description('新增文章，展示给客户查看的详细信息');
            if ($request->isMethod('post')) {
                $service = new ArticleService();
                $result = $service->create($input);
                if ($result['status'] == 1) {
                    return response()->redirectTo('/admin/article/edit/'.$result['data']->id);
                }
                return response()->json($result);
            }
            $bodyString = $this->formRender();
            $content->body($bodyString);
        });
    }

    /**
     * Make a form builder.
     *
     * @return string
     */
    public function formRender($action='/admin/article/create')
    {
        return Admin::form(ArticleArticleModel::class, function (Form $form) use($action) {
            $form->setAction($action);
            $form->text('title', '文章标题')->rules('required');
            //$form->textarea('content', '书写文章的内容')->rules('required');
            $form->editor('content','文章内容');
        })->render();
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form($action='/admin/article/create')
    {
        return Admin::form(ArticleArticleModel::class, function (Form $form) use($action) {
            $form->setAction($action);
            $form->text('title', '文章标题')->rules('required');
            //$form->textarea('content', '书写文章的内容')->rules('required');
            $form->editor('content','文章内容');
        });
    }
}
