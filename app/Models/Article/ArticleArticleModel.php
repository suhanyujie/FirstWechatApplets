<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/10/14
 * Time: 下午12:39
 */

namespace App\Models\Article;

use App\Models\BaseModel;

class ArticleArticleModel extends BaseModel
{
    //自定义表名
    protected $table = 'article_article';

    public const CREATED_AT = 'add_time';

    public const UPDATED_AT = 'update_time';

    protected $fillable = [
        "id",
        "title",
        "content",
        'a_status',
        'add_time',
        'update_time',
    ];

    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable($this->table);

        parent::__construct($attributes);
    }
}
