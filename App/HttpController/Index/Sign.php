<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/8
 * Time: 15:00
 */

namespace App\HttpController\Index;


use App\HttpController\Base\ViewController;
use App\Model\Pool\Redis\Redis;
use App\Task\SendCodeSms;

use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;

class Sign extends ViewController
{
    public function login()
    {
        $this->session()->set('user', null);
        if($this->isAjax()){
            $phone = $this->request()->getRequestParam('phone');
            $key = Config::getInstance()->getConf('redis.pre.sms_code') . $phone;
            $code = (new Redis())->get($key);
            if($code && $code == $this->request()->getRequestParam('pnum')){
                $this->session()->set('user', ['phone' => $phone]);
                return $this->successReturn('登陆成功');
            }else{
                return $this->errorReturn('验证码不正确,请重新获取');
            }
        }
        return $this->fetch('login');
    }

    public function logout()
    {
        $this->session()->set('user', null);
        return $this->successReturn('退出成功');
    }

    public function sendCode(){
        if($this->isAjax()){
            $phone = $this->request()->getRequestParam('phone');
            $fd = $this->request()->getRequestParam('fd') ?: 0;
            TaskManager::async(new SendCodeSms(['phone' => $phone, 'fd' => (int)$fd]));
            return $this->successReturn('发送成功');
        }
        return $this->errorReturn('非法访问');
    }
}