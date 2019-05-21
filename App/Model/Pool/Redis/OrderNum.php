<?php
/**
 * 订单表自增ID
 * Author: chenlei
 * Date: 2019/5/20
 * Time: 11:22
 */

namespace App\Model\Pool\Redis;


class OrderNum
{
    public $db = 8;
    private $key = 'orderNo';
    private $redis = null;

    public function __construct()
    {
        $this->redis = new Redis($this->db);
    }

    public function incr()
    {
        return $this->redis->incr($this->key);
    }
}