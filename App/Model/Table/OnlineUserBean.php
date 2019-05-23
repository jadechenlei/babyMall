<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/23
 * Time: 16:51
 */

namespace App\Model\Table;


use EasySwoole\Spl\SplBean;

class OnlineUserBean extends SplBean
{
    protected $fd;
    protected $uid;

    /**
     * FdGetter
     * @return mixed
     */
    public function getFd()
    {
        return $this->fd;
    }

    /**
     * FdSetter
     * @param mixed $fd
     */
    public function setFd($fd): void
    {
        $this->fd = $fd;
    }

    /**
     * OpenidGetter
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * OpenidSetter
     * @param mixed $uid
     */
    public function setUid($uid): void
    {
        $this->uid = $uid;
    }
}