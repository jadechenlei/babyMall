<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/20
 * Time: 18:28
 */

namespace App\HttpController\Index;


use App\HttpController\Base\ViewController;
use App\Model\Pool\Mysql\Goods as GoodsModel;
use App\Model\Pool\Redis\IndexCache;

class Goods extends ViewController
{
    public function index()
    {
        return $this->fetch('index');
    }

    public function detail()
    {
        if (!isset($this->param['id'])) {
            return $this->response()->redirect("/");
        }
        $goods = GoodsModel::where('id', $this->param['id'])->find();
        if(!$goods){
            return $this->response()->redirect("/");
        }
        $this->assign(['goods' => $goods]);

        $recommend = (new IndexCache())->find();
        $this->assign(['recommend' => array_splice($recommend, 0, 6)]);

        return $this->fetch('detail');
    }
}