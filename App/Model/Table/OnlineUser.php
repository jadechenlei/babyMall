<?php
/**
 * 在线用户信息存储在swoole的table中
 * Author: chenlei
 * Date: 2019/5/23
 * Time: 16:49
 */

namespace App\Model\Table;


use EasySwoole\Component\Singleton;
use EasySwoole\Component\TableManager;
use Swoole\Table;

class OnlineUser
{
    use Singleton;

    private $table;

    function __construct()
    {
        TableManager::getInstance()->add('onlineUser', [
            'fd' => ['type' => Table::TYPE_INT, 'size' => 4],
            'uid' => ['type' => Table::TYPE_STRING, 'size' => 11]
        ]);
        $this->table = TableManager::getInstance()->get('onlineUser');
    }

    /**
     * 创建在线用户
     * @param OnlineUserBean $userBean
     * @return mixed
     */
    function createUser(OnlineUserBean $userBean)
    {
        $data = $userBean->toArray(null, $userBean::FILTER_NOT_EMPTY);
        return $this->table->set($userBean->getFd(), $data);
    }

    /**
     * 删除在线用户
     * @param $fd
     */
    function deleteUser($fd)
    {
        $user = $this->table->get($fd);
        if ($user) {
            $this->table->del($fd);
        }
    }

    /**
     * 获取某个用户的信息
     * @param $fd
     * @return mixed
     */
    function getUser($fd)
    {
        return $this->table->get($fd);
    }

    /**
     * 获取所有用户
     * @return array
     */
    function getUsers()
    {
        $users = [];
        foreach ($this->table as $user) {
            $users[] = [
                'uid' => $user['uid']
            ];
        }
        return $users;
    }
}