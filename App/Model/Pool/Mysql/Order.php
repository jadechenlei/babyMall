<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/17
 * Time: 17:36
 */

namespace App\Model\Pool\Mysql;


class Order extends Model
{
    protected $createTime = true;
    protected $createTimeName = 'create_time';

    public function cancelOrder(array $ids)
    {
        return self::where(['id' => ['in' => $ids], 'status' => 1])->edit(['status' => 3, 'cancel_time' => time()]);
    }
}