<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/10
 * Time: 14:38
 */

namespace App\HttpController\Validate;


class Validate
{
    protected $rule = [];

    protected $message = [];

    protected $scene = [];

    public function check($data)
    {
        if (!empty($this->rule)) {
            $valitor = new \EasySwoole\Validate\Validate();
            foreach ($this->rule as $k => $v) {
                $k = explode('|', $k);
                echo $k[0];
                $valitorSet = $valitor->addColumn($k[0]);
                $name = $k[1];
                $v = explode('|', $v);
                foreach ($v as $vv) {
                    $msg = $this->message["{$k[0]}.{$vv}"] ?? '';
                    echo $msg;
                    switch ($vv) {
                        case 'require';
                            $valitorSet = $valitorSet->required($name . '必填');
                            break;
                        default;
                            $valitorSet = $valitorSet->func($this->checkCode(), $msg);
                            break;
                    }
                }
            }
            $valitor->validate($data);
            echo $valitor->getError()->__toString();
            return $valitor->validate($data) ? true : $valitor->getError()->__toString();
        }
        return true;
    }
}