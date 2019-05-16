<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/10
 * Time: 14:33
 */

namespace App\HttpController\Validate\Index\Index;


use App\HttpController\Validate\Validate;

class Sign extends Validate
{
    protected $rule = [
       // 'pnum|验证码' => 'require|checkCode',
    ];

    protected $message = [
        'pnum.checkCode' => '验证码不正确,请重新获取',
    ];

    protected $scene = [
        'add' => ['region_code', 'op'],
    ];

    public function checkCode()
    {

    }
}
