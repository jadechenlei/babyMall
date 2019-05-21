<?php
/**
 * 用于对订单支付状态监控的有序集合
 * Author: chenlei
 * Date: 2019/5/20
 * Time: 14:36
 */

namespace App\Model\Pool\Redis;


class OrderStatus
{
    public $db = 10;
    private $redis = null;
    public $key = 'order_status';

    public function __construct($obj = null)
    {
        $this->redis = new Redis($this->db);
    }

    public function zadd($orderId, $time)
    {
        return $this->redis->zAdd($this->key, $time, $orderId);
    }

    public function getOrders($end = -1)
    {
        $end = $end == -1 ? time() : $end;
        return $this->redis->zRangeByScore($this->key, 0, $end);
    }
}