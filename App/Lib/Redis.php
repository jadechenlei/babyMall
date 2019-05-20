<?php
/**
 * 非连接池版本的redis
 * Author: chenlei
 * Date: 2019/5/20
 * Time: 16:13
 */

namespace App\Lib;


use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;

class Redis
{
    use Singleton;

    public $redis = "";

    private function __construct()
    {
        ini_set('default_socket_time', -1);
        if (!extension_loaded('redis')) {
            throw new \Exception("redis.so文件不存在");
        }
        try {
            $redisConfig = Config::getInstance()->getConf("redis");
            $this->redis = new \Redis();
            $result = $this->redis->connect($redisConfig['host'], $redisConfig['port']);
            $this->redis->auth($redisConfig['auth']);
            print_r($result);
        } catch (\Exception $e) {
            throw new \Exception("redis服务异常");
        }
        if ($result === false) {
            throw new \Exception("redis 链接失败");
        }
    }

    /**
     * 当类中不存在该方法时候，直接调用call 实现调用底层redis相关的方法
     * @auth   singwa
     * @param  [type] $name      [description]
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     */
    public function __call($name, $arguments)
    {
        return $this->redis->$name(...$arguments);
    }
}