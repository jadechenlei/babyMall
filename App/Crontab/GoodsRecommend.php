<?php
/**
 * 商品推荐
 * Author: chenlei
 * Date: 2019/5/16
 * Time: 16:45
 */

namespace App\Crontab;


use App\Lib\AlarmNotice;
use App\Model\Pool\Mysql\Goods;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class GoodsRecommend extends AbstractCronTask
{
    // 定时任务规则
    public static function getRule(): string
    {
        return '*/1 * * * *';
    }

    // 定时任务名称
    public static function getTaskName(): string
    {
        return 'GoodsRecommend';
    }

    // 定时任务处理逻辑
    static function run(\swoole_server $server, $taskId, $fromWorkerId, $flags = null)
    {
        $goods = Goods::limit(20)->groupBy('title')->orderBy('RAND()')->select();
        $path = Config::getInstance()->getConf('datacache.path.goods_recommend');
        $flags = file_put_contents($path, json_encode($goods, 256));
        if (empty($flags)) {
            AlarmNotice::send('商品推荐静态化Json写入失败');
        } else {
            var_dump('cron:recommend write succss' . date('Y-m-d H:i:s'));
        }
    }
}