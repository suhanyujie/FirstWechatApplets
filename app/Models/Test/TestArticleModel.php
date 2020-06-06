<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-06-06
 * Time: 14:56
 */

namespace App\Models\Test;


use App\Models\BaseModel;

class TestArticleModel extends BaseModel
{
    public $table = 'test_article';

    public $timestamps = false;
}
