<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/10
 * Time: 9:45
 */

namespace App\WebSocket;


use EasySwoole\Socket\AbstractInterface\Controller;

class SmsCode extends Controller
{
    public function getFd()
    {
        $this->response()->setMessage(json_encode(['type' => 'fd', 'data' => $this->caller()->getClient()->getFd()]));
    }
}