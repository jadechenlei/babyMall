<?php
/**
 * 异步任务发送登陆手机验证码
 * Author: chenlei
 * Date: 2019/5/9
 * Time: 10:11
 */

namespace App\Task;


use App\Lib\AlarmNotice;
use App\Lib\SendThirdMsg;
use App\Model\Pool\Redis\Redis;
use App\WebSocket\SmsCode;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Swoole\Task\AbstractAsyncTask;
use EasySwoole\EasySwoole\Config;

class SendCodeSms extends AbstractAsyncTask
{
    /**
     * 执行任务的内容
     * @param mixed $taskData 任务数据
     * @param int $taskId 执行任务的task编号
     * @param int $fromWorkerId 派发任务的worker进程号
     * @author : evalor <master@evalor.cn>
     */
    function run($taskData, $taskId, $fromWorkerId,$flags = null)
    {
        try {
            sleep(3);
            $code = \EasySwoole\Utility\Random::number(4);
            $key = Config::getInstance()->getConf('redis.pre.sms_code').$taskData['phone'];
            (new Redis())->set($key,$code,600);
            SendThirdMsg::Sms($code, 1816274968);
            if ($taskData['fd']) {
                ServerManager::getInstance()->getSwooleServer()->push($taskData['fd'],
                    json_encode(['type' => 'code', 'data' => $code]));
            }
            echo "验证码已发:{$code}". "\n";
        } catch (\Exception $e) {
            AlarmNotice::send('验证码短信出错,请尽快处理',$e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 任务执行完的回调
     * @param mixed $result 任务执行完成返回的结果
     * @param int $task_id 执行任务的task编号
     * @author : evalor <master@evalor.cn>
     */
    function finish($result, $task_id)
    {
        // 任务执行完的处理
    }
}