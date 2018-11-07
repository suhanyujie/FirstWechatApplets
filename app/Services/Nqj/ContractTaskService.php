<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/6
 * Time: 下午10:30
 */

namespace App\Services\Nqj;

use App\Services\BaseService;
use App\Models\Nqj\ContractTaskModel;

class ContractTaskService extends BaseService
{
    /**
     * @desc 合同任务的消费
     */
    public function taskConsume()
    {
        $tasks = $this->getList();
        if (!$tasks)return ['status'=>1,'msg'=>'暂时没有合同任务！'];
        foreach ($tasks as $k=>$task) {
            try{
                $res = $this->dealOne($task);
            }catch (\Exception $e) {
                $res = ['status'=>$e->getCode(),'msg'=>$e->getMessage()];
            }
            $this->writeLog($res);
        }
    }

    /**
     * @desc 处理一个合同任务
     */
    public function dealOne($task=[])
    {
        //todo

        return ['status'=>1,'msg'=>"任务：{$task['tid']}完成\n"];
    }

    /**
     * @desc 记录每个任务日志
     * @param array $logData
     */
    public function writeLog($logData=[])
    {

    }

    /**
     * @desc 获取数据列表
     * @param array $paramArr
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getList($paramArr = [])
    {
        $options = [
            'id'      => '',// 可选 int|array 模型对应表的主键值，多个传数组，如[12,14,16]
            'status'  => '',
            'type'    => '',
            'fields'  => '*',// string 查询字段
            'isCount' => '',// 可选：1 是否只返回数据的数量
            'debug'   => '',// 可选：1 调试，为true时，打印出sql语句
            'offset'  => 0,// 可选 int mysql查询数据的偏移量
            'limit'   => 1,// 可选 int mysql查询数据的条数限制
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        $model = new ContractTaskModel();
        if (!empty($id)) {
            if (is_array($id)) {
                $model = $model->whereIn('id', $id);
            } else {
                $model = $model->where('id', $id);
            }
        }
        if (!empty($test_company_id)) {
            $model = $model->where('test_company_id', $test_company_id);
        }
        if (!empty($type)) {
            $model = $model->where('type', $type);
        }
        if (!empty($status)) {
            $model = $model->where('status', $status);
        }
        if (!empty($isCount)) {
            return $model->count();
        }
        //order
        if (!empty($order)) {
            foreach ($order as $orderField => $orderDir) {
                $model = $model->order($orderField, $orderDir);
            }
        } else {
            $model = $model->orderby($model->getKeyName(), 'desc');
        }
        $model = $model->offset($options['offset'])->limit($options['limit']);
        if (!empty($debug)) {
            echo $model->toSql();exit();
        }
        $data = $model->get([$options['fields']]);

        return $data;
    }
}
