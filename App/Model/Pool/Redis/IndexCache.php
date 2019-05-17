<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/17
 * Time: 15:31
 */

namespace App\Model\Pool\Redis;


class IndexCache
{
    public $db = 2;
    public $key = 'goods_recommend:';

    public function add(array $data)
    {
        return (new Redis($this->db))->set($this->key, json_encode($data, 256));
    }

    public function find(): array
    {
        $goods = (new Redis($this->db))->get($this->key);
        return $goods ? json_decode($goods, true) : [];
    }
}