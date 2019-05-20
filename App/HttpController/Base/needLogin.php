<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/17
 * Time: 11:55
 */

namespace App\HttpController\Base;


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
                return true;
            }
        }
        return false;
    }
}