<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/9
 * Time: 15:32
 */
return [
    'host' => '127.0.0.1',
    'port' => 6379,
    'auth' => '123456',
    'pool_max_num' => 4,
    'pool_min_num' => 1,
    'pool_time_out' => 0.1,
    'pre' => [
        'sms_code' => 'sms_code:'
    ]
];
