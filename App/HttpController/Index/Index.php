<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/7
 * Time: 18:30
 */

namespace App\HttpController\Index;


use App\HttpController\Base\ViewController;
use App\Model\Pool\Mysql\Goods;
use EasySwoole\EasySwoole\Config;

class Index extends ViewController
{
    function index()
    {
        $path = Config::getInstance()->getConf('datacache.path.goods_recommend');
        if (file_exists($path)) {
            $goods = file_get_contents($path);
        }else{
            $goods = Goods::limit(20)->groupBy('title')->select();
        }
        $this->getView()->assign(['goods' => json_decode($goods, true)]);
        return $this->fetch('index');
    }
}