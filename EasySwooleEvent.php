<<<<<<< HEAD
<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Bin\LoadConf;
use App\Lib\Redis;
use App\Pool\MysqlPool;
use App\Pool\RedisPool;
use App\Process\HotReload;
use App\Process\OrderStatus;
use App\WebSocket\WebSocketEvent;
use App\WebSocket\WebSocketParser;
use EasySwoole\Component\Di;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Mysqli\Mysqli;
use EasySwoole\Socket\Dispatcher;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        // 加载配置项
        (new LoadConf())->run();

        // 注册redis连接池
        $max = Config::getInstance()->getConf('database.pool_max_num');
        $min = Config::getInstance()->getConf('database.pool_min_num');
        $redisConf = PoolManager::getInstance()->register(RedisPool::class, $max);
        $redisConf->setMaxObjectNum($max)->setMinObjectNum($min);

        // 注册mysql连接池
        $mysqlConf = PoolManager::getInstance()->register(MysqlPool::class, $max);
        $mysqlConf->setMaxObjectNum($max)->setMinObjectNum($min);

    }

    /**
     * @param EventRegister $register
     * @throws \EasySwoole\Socket\Exception\Exception
     */
    public static function mainServerCreate(EventRegister $register)
    {
        #注册非连接池的数据库链接,用于脚本等一些情况
        $conf = new \EasySwoole\Mysqli\Config(Config::getInstance()->getConf('database'));
        Di::getInstance()->set('MYSQL',new Mysqli($conf));
        Di::getInstance()->set('REDIS', Redis::getInstance());

        $swooleServer = ServerManager::getInstance()->getSwooleServer();
        $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
        $swooleServer->addProcess((new OrderStatus('OrderStatus'))->getProcess());

        /**
         * **************** websocket控制器 **********************
         */
        // 创建一个 Dispatcher 配置
        $conf = new \EasySwoole\Socket\Config();
        // 设置 Dispatcher 为 WebSocket 模式
        $conf->setType($conf::WEB_SOCKET);
        // 设置解析器对象
        $conf->setParser(new WebSocketParser());
        // 创建 Dispatcher 对象 并注入 config 对象
        $dispatch = new Dispatcher($conf);
        // 给server 注册相关事件 在 WebSocket 模式下  message 事件必须注册 并且交给 Dispatcher 对象处理
        $register->set(EventRegister::onMessage,
            function (\swoole_websocket_server $server, \swoole_websocket_frame $frame) use ($dispatch) {
                $dispatch->dispatch($server, $frame->data, $frame);
            });
        //自定义握手
        $websocketEvent = new WebSocketEvent();
        $register->set(EventRegister::onHandShake,
            function (\swoole_http_request $request, \swoole_http_response $response) use ($websocketEvent) {
                $websocketEvent->onHandShake($request, $response);
            });
        $register->set(EventRegister::onClose,
            function (\swoole_server $server, int $fd, int $reactorId) use ($websocketEvent) {
                $websocketEvent->onClose($server, $fd, $reactorId);
            });

        /**
         * **************** crontab计划任务 **********************
         */
        $cron = Config::getInstance()->getConf('crontab');
        if (is_array($cron) && !empty($cron)) {
            foreach ($cron as $t) {
                if (class_exists($t)) {
                    Crontab::getInstance()->addTask($t);
                }
            }
        }
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
=======
<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Bin\LoadConf;
use App\Lib\Redis;
use App\Pool\MysqlPool;
use App\Pool\RedisPool;
use App\Process\HotReload;
use App\Process\OrderStatus;
use App\WebSocket\WebSocketEvent;
use App\WebSocket\WebSocketParser;
use EasySwoole\Component\Di;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Mysqli\Mysqli;
use EasySwoole\Socket\Dispatcher;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        // 加载配置项
        (new LoadConf())->run();

        // 注册redis连接池
        $max = Config::getInstance()->getConf('database.pool_max_num');
        $min = Config::getInstance()->getConf('database.pool_min_num');
        $redisConf = PoolManager::getInstance()->register(RedisPool::class, $max);
        $redisConf->setMaxObjectNum($max)->setMinObjectNum($min);

        // 注册mysql连接池
        $mysqlConf = PoolManager::getInstance()->register(MysqlPool::class, $max);
        $mysqlConf->setMaxObjectNum($max)->setMinObjectNum($min);

    }

    /**
     * @param EventRegister $register
     * @throws \EasySwoole\Socket\Exception\Exception
     */
    public static function mainServerCreate(EventRegister $register)
    {
        #注册非连接池的数据库链接,用于脚本等一些情况
        $conf = new \EasySwoole\Mysqli\Config(Config::getInstance()->getConf('database'));
        Di::getInstance()->set('MYSQL',new Mysqli($conf));
        Di::getInstance()->set('REDIS', Redis::getInstance());

        $swooleServer = ServerManager::getInstance()->getSwooleServer();
        $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
        $swooleServer->addProcess((new OrderStatus('OrderStatus'))->getProcess());

        /**
         * **************** websocket控制器 **********************
         */
        // 创建一个 Dispatcher 配置
        $conf = new \EasySwoole\Socket\Config();
        // 设置 Dispatcher 为 WebSocket 模式
        $conf->setType($conf::WEB_SOCKET);
        // 设置解析器对象
        $conf->setParser(new WebSocketParser());
        // 创建 Dispatcher 对象 并注入 config 对象
        $dispatch = new Dispatcher($conf);
        // 给server 注册相关事件 在 WebSocket 模式下  message 事件必须注册 并且交给 Dispatcher 对象处理
        $register->set(EventRegister::onMessage,
            function (\swoole_websocket_server $server, \swoole_websocket_frame $frame) use ($dispatch) {
                $dispatch->dispatch($server, $frame->data, $frame);
            });
        //自定义握手
        $websocketEvent = new WebSocketEvent();
        $register->set(EventRegister::onHandShake,
            function (\swoole_http_request $request, \swoole_http_response $response) use ($websocketEvent) {
                $websocketEvent->onHandShake($request, $response);
            });
        $register->set(EventRegister::onClose,
            function (\swoole_server $server, int $fd, int $reactorId) use ($websocketEvent) {
                $websocketEvent->onClose($server, $fd, $reactorId);
            });

        /**
         * **************** crontab计划任务 **********************
         */
        $cron = Config::getInstance()->getConf('crontab');
        if (is_array($cron) && !empty($cron)) {
            foreach ($cron as $t) {
                if (class_exists($t)) {
                    Crontab::getInstance()->addTask($t);
                }
            }
        }
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
>>>>>>> ec4301511d7901bb04475b5f212d3ea2f96b3d3f
}