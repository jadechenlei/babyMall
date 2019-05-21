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
use App\Model\Pool\Redis\IndexCache;

class Index extends ViewController
{
    public function index()
    {
        $goods = (new IndexCache())->find();
        $this->getView()->assign(['goods' => $goods]);
        return $this->fetch('index');
    }
}