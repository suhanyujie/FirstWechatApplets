<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/10/14
 * Time: 下午12:43
 */

namespace App\Services\Article;

use App\Models\Article\ArticleArticleModel;
use App\Services\BaseService;

class ArticleService extends BaseService
{
    /**
     * 创建新文章
     * @param array $paramArr
     * @return array
     */
    public function create($paramArr=[])
    {
        $options = [
            'title'   => '',
            'content' => '',
        ];
        $options = array_merge($options, $paramArr);
        if (empty($options['add_time'])) {
            $options['add_time'] = date('Y-m-d H:i:s');
        }
        if (empty($options['a_status'])) {
            $options['a_status'] = 1;
        }
        try{
            $result = ArticleArticleModel::create($options);
        }catch (\Exception $e) {
            return ['status'=>300026, 'message'=>$e->getMessage()];
        }
        if ($result) {
            return ['status'=>1,'message'=>'新增数据成功！'];
        }else{
            return ['status'=>300029,'message'=>'新增数据失败！'];
        }
    }

    /**
     * 编辑文章
     * @param array $paramArr
     * @return array
     */
    public function edit($paramArr=[])
    {
        $options = [
            'id'          => '',
            'title'       => '',
            'content'     => '',
            'update_time' => '',
        ];
        $options = array_merge($options, $paramArr);
        if (empty($options['update_time'])) {
            $options['update_time'] = date('Y-m-d H:i:s');
        }
        $model = new ArticleArticleModel();
        $article = $model->find($options['id']);
        $article->title = $options['title'];
        $article->content = $options['content'];
        $article->update_time = $options['update_time'];
        $result = $article->save();
        if ($result) {
            return ['status'=>1,'message'=>'编辑数据成功！'];
        }else{
            return ['status'=>300029,'message'=>'编辑数据失败！'];
        }
    }

    /**
     * 展示文章
     * @param array $paramArr
     * @return array
     */
    public function detail($paramArr=[])
    {
        $options = [
            'id' => '',
        ];
        $options = array_merge($options, $paramArr);
        $model = new ArticleArticleModel();
        $article = $model->find($options['id']);
        if (is_null($article)) {
            return ['status'=>200036,'message'=>'该文章不存在！'];
        }

        return ['status'=>1, 'data'=>$article,'message'=>'获取成功！'];
    }
}
