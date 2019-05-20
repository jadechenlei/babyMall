<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/20
 * Time: 15:18
 */

namespace App\HttpController\Index;


use App\HttpController\Base\ViewController;
use App\Model\Pool\Redis\OrderStatus;
use EasySwoole\Component\Di;
use EasySwoole\Mysqli\Mysqli;

class Test extends ViewController
{
    public function index()
    {
        /*$time = time() - 60*100;
        $res = (new OrderStatus())->getOrders(time() - 60*1);*/
        /*$conf = new \EasySwoole\Mysqli\Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf('database'));
        $db = new Mysqli($conf);*/
        /*$db = Di::getInstance()->get("REDIS");
        $db->select(10);
        $ids = $db->zRangeByScore('order_status', 0, -1);*/
        $OrderStatusSort = new OrderStatus(Di::getInstance()->get("REDIS"));
        $ids = $OrderStatusSort->getOrders();

        return $this->dump($ids);
    }
}