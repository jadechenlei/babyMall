<?php
/**
 * 监控订单支付超时状态
 * Author: chenlei
 * Date: 2019/5/20
 * Time: 15:06
 */

namespace App\Process;


use App\Lib\AlarmNotice;
use App\Model\Pool\Mysql\Order;
use EasySwoole\Component\Di;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\EasySwoole\Config;
use Swoole\Timer;
use App\Model\Pool\Redis\OrderStatus as OrderStatusSort;

class OrderStatus extends AbstractProcess
{
    public function run($arg)
    {
        try {
            $execTime = Config::getInstance()->getConf('app.order_exec_time');
            Timer::tick(1000, function () use ($execTime) {
                $OrderStatusSort = new OrderStatusSort();
                $ids = $OrderStatusSort->getOrders(time() - 60 * $execTime);
                (new Order())->cancelOrder($ids);
            });
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }

    public function onReceive($str)
    {
        // TODO: Implement onReceive() method.
    }
}