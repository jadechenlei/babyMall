<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/17
 * Time: 11:55
 */

namespace App\HttpController\Base;


use App\Model\Pool\Redis\Cart;

class needLogin extends ViewController
{
    public function onRequest($action): ?bool
    {
        if (parent::onRequest($action)) {
            if (!$this->session()->get('user')) {
                if ($this->isAjax()) {
                    $this->errorReturn('login');
                } else {
                    $this->response()->redirect("/index/sign/login");
                }
            } else {
                $this->assign(['cartTotal' => (new Cart())->getTotal($this->userId)]);
                return true;
            }
        }
        return false;
    }
}