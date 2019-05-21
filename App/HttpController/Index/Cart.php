<?php
/**
 * 购物车
 * Author: chenlei
 * Date: 2019/5/17
 * Time: 11:54
 */

namespace App\HttpController\Index;


use App\HttpController\Base\needLogin;
use App\Model\Pool\Mysql\Goods;
use App\Model\Pool\Redis\Cart as CartRedis;

class Cart extends needLogin
{
    public function index()
    {
        $CartRedis = new CartRedis();
        $ids = $CartRedis->getGoodsIds($this->userId);
        if ($ids) {
            $goods = Goods::where(['id' => ['in', $ids]])->select();
            foreach ($goods as &$v) {
                $v['num'] = $CartRedis->getGoodsNum($this->userId, $v['id']);
                $v['total'] = $v['num'] * $v['price'];
            }
        } else {
            $goods = [];
        }
        $this->assign(['goods' => $goods]);
        return $this->fetch('index');
    }

    public function add()
    {
        $user = $this->userId;
        $goodsId = $this->param['goods_id'];
        $num = $this->param['num'];
        $addRedis = (new CartRedis())->add($user, $goodsId, (int)$num);
        if ($addRedis) {
            return $this->successReturn('添加成功,使用websocket更新所有页面的购物车数量');
        } else {
            return $this->errorReturn();
        }
    }
}