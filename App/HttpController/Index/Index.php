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

class Index extends ViewController
{
    function index()
    {
        (new Goods())->add([
            'title'=>'dd'
        ]);
        return $this->fetch('index');
    }
}
