<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/8
 * Time: 11:13
 */

namespace App\Bin;


use EasySwoole\EasySwoole\Config;
use EasySwoole\Utility\File;

class LoadConf
{
    public function run($ConfPath = '/App/Config')
    {
        $Conf = Config::getInstance();
        $files = File::scanDirectory(EASYSWOOLE_ROOT . $ConfPath);
        if (!is_array($files)) {
            return;
        }
        foreach ($files['files'] as $file) {
            $data = require_once $file;
            $Conf->setConf(strtolower(basename($file, '.php')), (array)$data);
        }
    }
}