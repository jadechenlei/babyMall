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

    public function down()
    {
        $file = file_get_contents(EASYSWOOLE_ROOT . '/public/data/zhiniaoku.csv');
        $data = explode(PHP_EOL, $file);
        foreach ($data as $v) {
            go(function () use ($v) {
                $vv = explode(",", $v);
                $p = strpos($vv[0], ' ') ?: 6;
                if (isset($vv[0]) && isset($vv[1]) && isset($vv[2])) {
                    $data = [
                        'title' => trim(substr($vv[0], 0, $p)),
                        'sub_title' => trim(substr($vv[0], $p)),
                        'price' => $vv[2],
                        'img' => $vv[1],
                        'ori' => $vv[1],
                        'cat_id' => 2
                    ];
                    #需注意mysql最大连接数， 默认最大连接数为100
                    $conf = new \EasySwoole\Mysqli\Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf('database'));
                    $db = new Mysqli($conf);
                    $res = $db->insert('bb_goods', $data);
                    echo $res . "\n";
                }
            });
        }

    }

    private function downImg($url)
    {
        ob_start(); //打开输出
        readfile($url); //输出图片文件
        $img = ob_get_contents(); //得到浏览器输出
        ob_end_clean(); //清除输出并关闭
        $name = \EasySwoole\Utility\Random::character(8) . '.jpg';
        $s = file_put_contents('./public/static/img/goods/' . $name, $img);
        return $name;
    }
}