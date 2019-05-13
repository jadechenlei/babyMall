<?php
namespace App\Pool;

use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;

class RedisPool extends AbstractPool
{
    protected function createObject()
    {
        // TODO: Implement createObject() method.
        $redis = new RedisObject();
        $conf = Config::getInstance()->getConf('redis');
        if( $redis->connect($conf['host'],$conf['port'])){
            $redis->auth($conf['auth']);
            return $redis;
        }else{
            return null;
        }
    }
}