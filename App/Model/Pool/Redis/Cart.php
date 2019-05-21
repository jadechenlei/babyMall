<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/17
 * Time: 15:56
 */

namespace App\Model\Pool\Redis;


class Cart
{
    public $db = 6;
    private $redis = null;

    public function __construct()
    {
        $this->redis = new Redis($this->db);
    }

    public function getGoodsIds($userId): array
    {
        return $this->redis->hKeys($userId) ?: [];
    }

    public function getGoodsNum($userId, $goodId)
    {
        return $this->redis->hGet($userId, $goodId);
    }

    public function getTotal($userId)
    {
        return array_sum($this->redis->hVals($userId));
    }

    public function goodsExist($userId, $goodId)
    {
        return $this->redis->hExists($userId, $goodId);
    }

    public function add($userId, $goodId, $num = 1)
    {
        if (!$userId || !$goodId || !$num) {
            return false;
        }
        if (!$this->goodsExist($userId, $goodId)) {
            return $this->redis->hSet($userId, $goodId, $num);
        } else {
            return $this->redis->hIncrBy($userId, $goodId, $num);
        }
    }
}