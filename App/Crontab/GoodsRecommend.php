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
        $recommend = Goods::limit(28)->where(['cat_id' => ['not in',[3,5,6]]])->groupBy('title')->orderBy('RAND()')->select() ?: [];
        $f1 = Goods::limit(5)->where(['cat_id' => ['=', 6]])->groupBy('title')->orderBy('RAND()')->select() ?: [];
        $f2 = Goods::limit(5)->where(['cat_id' => ['=', 5]])->groupBy('title')->orderBy('RAND()')->select() ?: [];
        $f3 = Goods::limit(5)->where(['cat_id' => ['=', 3]])->groupBy('title')->orderBy('RAND()')->select() ?: [];
        $goods = [
            'recommend' => $recommend,
            'f1' => $f1,
            'f2' => $f2,
            'f3' => $f3,
        ];
        $flags = (new IndexCache())->add($goods);
        if (!$flags) {
            AlarmNotice::send('商品推荐静态化Json写入失败');
        }
    }
}