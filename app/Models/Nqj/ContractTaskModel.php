<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/6
 * Time: 下午10:37
 */

namespace App\Models\Nqj;

use App\Models\BaseModel;

class ContractTaskModel extends BaseModel
{
    //自定义表名
    protected $table = 'nqj_contract_task';

    protected $primaryKey = 'tid';

    public const CREATED_AT = 'add_time';

    public const UPDATED_AT = 'update_time';

    protected $connection = 'localhostBbsTest';

    protected $fillable = [
        'tid',
        'task_data',
        't_status',
        'create_time',
        'update_time',
    ];

//    public function __construct(array $attributes = [])
//    {
//        $connection = config('database.connections.localhostBbsTest');
//
//        $this->setConnection($connection);
//
//        $this->setTable($this->table);
//
//        parent::__construct($attributes);
//    }
}
