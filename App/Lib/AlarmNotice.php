<?php
/**
 * 处理一些重要的报警信息,需要短信/邮件通知给负责人
 * Author: chenlei
 * Date: 2019/5/9
 * Time: 10:17
 */

namespace App\Lib;


class AlarmNotice
{
    static function send($msg, $receiver = 1, $type = 1)
    {
        echo $msg . "\n";
        return true;
    }
}