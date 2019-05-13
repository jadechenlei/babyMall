<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/9
 * Time: 16:27
 */

namespace App\Model\Pool\Redis;


use App\Pool\RedisObject;
use App\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;

class Redis
{
    public $redis;

    public function __construct()
    {
        $timeout = Config::getInstance()->getConf('redis.pool_time_out');
        $redisObject = PoolManager::getInstance()->getPool(RedisPool::class)->getObj($timeout);
        // 类型的判定
        if ($redisObject instanceof RedisObject) {
            $this->redis = $redisObject;
        } else {
            throw new \Exception('Redis Pool is error');
        }
    }

    public function set($key, $value, $time = 0)
    {
        if (empty($key)) {
            return '';
        }
        if (is_array($value)) {
            $value = json_encode($value, 256);
        }
        if (!$time) {
            $res = $this->redis->set($key, $value);
        } else {
            $res = $this->redis->setex($key, $time, $value);
        }
        if (!$res) {
            throw new \Exception('Redis Set Fail');
        }
        return $res;
    }

    public function __call($name, $arguments)
    {

        ///var_dump(...$arguments);
        return $this->redis->$name(...$arguments);
    }

    public function __destruct()
    {
        if ($this->redis instanceof RedisObject) {
            PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($this->redis);
            $this->redis = null;
        }
    }
}