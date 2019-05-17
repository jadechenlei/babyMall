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
use App\Model\Pool\Redis\IndexCache;
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
        $goods = Goods::limit(20)->groupBy('title')->orderBy('RAND()')->select() ?: [];
        $flags = (new IndexCache())->add($goods);
        if (!$flags) {
            AlarmNotice::send('商品推荐静态化Json写入失败');
        }
    }
}