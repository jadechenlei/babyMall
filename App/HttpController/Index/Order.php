<<<<<<< HEAD
<?php
/**
 * 订单
 * Author: chenlei
 * Date: 2019/5/17
 * Time: 17:08
 */

namespace App\HttpController\Index;


use App\HttpController\Base\needLogin;
use App\Model\Pool\Mysql\Order as OrderModel;
use App\Model\Pool\Redis\Cart as CartRedis;
use App\Model\Pool\Redis\OrderNum;
use App\Model\Pool\Redis\OrderStatus;

class Order extends needLogin
{
    public function index()
    {
        $order = OrderModel::join('goods', 'order.goods_id = goods.id')
            ->where('user_id', $this->userId)
            ->field('order_no,num,title,sub_title,price,img,order.create_time,order.status,order.cancel_time')
            ->order('order.create_time')
            ->limit(20)
            ->select();
        $this->assign(['order' => $order]);
        return $this->fetch('index');
    }

    public function add()
    {
        if (isset($this->param['goods_id'])) {
            $ids = [(int)$this->param['goods_id']];
            $num = (int)$this->param['num'];
        } else {
            $CartRedis = new CartRedis();
            $ids = $CartRedis->getGoodsIds($this->userId);
            $num = 0;
        }
        if (empty($ids)) {
            return $this->errorReturn('没有商品信息');
        }
        $orderModel = new OrderModel;
        $orderStatus = new OrderStatus();
        foreach ($ids as $id) {
            $num = $num ?: $CartRedis->getGoodsNum($this->userId, $id);
            $sort = (new OrderNum())->incr();
            $data = [
                'sort' => $sort,
                'order_no' => \EasySwoole\Utility\SnowFlake::make($sort, 1),
                'user_id' => $this->userId,
                'goods_id' => (int)$id,
                'num' => $num,
                'total' => 100
            ];
            $res = $orderModel->add($data);
            if ($res) {
                $orderStatus->zadd($res, time());
            } else {
                return $this->errorReturn('数据库添加失败');
            }
        }
        return $this->successReturn('下单成功,1分钟后订单将自动取消');
    }
=======
<?php
/**
 * 订单
 * Author: chenlei
 * Date: 2019/5/17
 * Time: 17:08
 */

namespace App\HttpController\Index;


use App\HttpController\Base\needLogin;
use App\Model\Pool\Mysql\Order as OrderModel;
use App\Model\Pool\Redis\Cart as CartRedis;
use App\Model\Pool\Redis\OrderNum;
use App\Model\Pool\Redis\OrderStatus;

class Order extends needLogin
{
    public function index()
    {
        $order = OrderModel::join('goods', 'order.goods_id = goods.id')
            ->where('user_id', $this->userId)
            ->field('order_no,num,title,sub_title,price,img,order.create_time,order.status,order.cancel_time')
            ->order('order.create_time')
            ->limit(20)
            ->select();
        $this->assign(['order' => $order]);
        return $this->fetch('index');
    }

    public function add()
    {
        $CartRedis = new CartRedis();
        $ids = $CartRedis->getGoodsIds($this->userId);
        if (empty($ids)) {
            return $this->errorReturn('没有商品信息');
        }
        $orderModel = new OrderModel;
        $orderStatus = new OrderStatus();
        foreach ($ids as $id) {
            $num = $CartRedis->getGoodsNum($this->userId, $id);
            $sort = (new OrderNum())->incr();
            $data = [
                'sort' => $sort,
                'order_no' => \EasySwoole\Utility\SnowFlake::make($sort, 1),
                'user_id' => $this->userId,
                'goods_id' => (int)$id,
                'num' => $num,
                'total' => 100
            ];
            $res = $orderModel->add($data);
            if ($res) {
                $orderStatus->zadd($res, time());
            } else {
                return $this->errorReturn('数据库添加失败');
            }
        }
        return $this->successReturn('下单成功,1分钟后订单将自动取消');
    }
>>>>>>> ec4301511d7901bb04475b5f212d3ea2f96b3d3f
}