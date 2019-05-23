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
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;

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
        $file = file_get_contents(EASYSWOOLE_ROOT . '/public/data/yunfuzhuang.csv');
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
                        'cat_id' => 8
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

    public function downImg()
    {
        $db = new \App\Model\Pool\Mysql\Goods();
        $data = $db->field('id,ori')->limit([1000, 4000])->select();
        foreach ($data as $v) {
            go(function () use ($v) {
                $accessKey = 'RicQtT4XSgAHwYDJRYjOnoOum37mzlg3HAUDSEkA';
                $secretKey = '29S_-Wgu7iAVfEFAEpgQTNApn2cJzkrsco0FFlvG';
                $bucket = 'qs';

                $auth = new Auth($accessKey, $secretKey);
                $bucketManager = new BucketManager($auth);

                $key = \EasySwoole\Utility\Random::character(8) . '.jpg';

                // 指定抓取的文件保存名称#curl协程是不支持协程,可使用swoole的异步客户端来解决
                $bucketManager->fetch($v['ori'], $bucket, $key);

                $conf = new \EasySwoole\Mysqli\Config(\EasySwoole\EasySwoole\Config::getInstance()->getConf('database'));
                $db2 = new Mysqli($conf);
                $img = 'http://cdn.static.questionfans.com/' . $key;
                $res = $db2->where('id', $v['id'])->update('bb_goods',['img' => $img]);
                #释放链接很重要
                $db2->resetDbStatus();
                //echo $res .$v['id']. "\n";
            });
        }
    }

    public function qiniu($url)
    {
        $accessKey = 'RicQtT4XSgAHwYDJRYjOnoOum37mzlg3HAUDSEkA';
        $secretKey = '29S_-Wgu7iAVfEFAEpgQTNApn2cJzkrsco0FFlvG';
        $bucket = 'qs';

        $auth = new Auth($accessKey, $secretKey);
        $bucketManager = new BucketManager($auth);

        $key = \EasySwoole\Utility\Random::character(8) . '.jpg';

        // 指定抓取的文件保存名称
        list($ret, $err) = $bucketManager->fetch($url, $bucket, $key);
        return $key;
    }
}