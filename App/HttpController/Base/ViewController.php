<?php
/**
 * Created by wwwscm.com
 * Author: chenlei
 * Date: 2019/5/8
 * Time: 10:39
 */

namespace App\HttpController\Base;


use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Session\SessionHandler;
use think\Template;

abstract class ViewController extends Controller
{
    private $view;
    protected $param;
    protected $user = [];
    protected $userId = '';

    /**
     * 初始化模板引擎
     * ViewController constructor.
     * @param string $actionName
     * @param Request $request
     * @param Response $response
     */
    public function __hook($actionName, $request, $response)
    {
        $this->init($actionName, $request, $response);
//        var_dump($this->view);
        parent::__hook($actionName, $request, $response);
    }

    /**
     * 输出模板到页面
     * @param  string|null $template 模板文件
     * @param array $vars 模板变量值
     * @param array $config 额外的渲染配置
     * @author : evalor <master@evalor.cn>
     */
    public function fetch($template = null, $vars = [], $config = [])
    {
        ob_start();
        $template == null && $template = $GLOBALS['base']['ACTION_NAME'];
        $this->view->fetch($template, $vars, $config);
        $content = ob_get_clean();
        $this->response()->write($content);
    }

    /**
     * @return Template
     */
    public function getView(): Template
    {
        return $this->view;
    }

    public function init(string $actionName, $request, $response)
    {
        $this->view = new Template();
        $tempPath = Config::getInstance()->getConf('app.temp_dir');     # 临时文件目录
        $class_name_array = explode('\\', static::class);
        $class_name_array_count = count($class_name_array);
        $controller_path
            = $class_name_array[$class_name_array_count - 2] . DIRECTORY_SEPARATOR . $class_name_array[$class_name_array_count - 1] . DIRECTORY_SEPARATOR;
//        var_dump(static::class);
        $this->view->config([
            'view_path' => EASYSWOOLE_ROOT . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'HttpController' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $controller_path,
            # 模板文件目录
            'cache_path' => "{$tempPath}/templates_c/",               # 模板编译目录
        ]);

        //var_dump(EASYSWOOLE_ROOT . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $controller_path);
    }

    public function assign(array $vars = [])
    {
        $this->getView()->assign($vars);
    }

    protected function dump($vars)
    {
        if (is_null($vars)) {
            return $this->response()->write('NULL');
        } elseif (is_bool($vars)) {
            return $this->response()->write($vars ? 'TRUE' : 'FALSE');
        } elseif (is_array($vars)) {
            return $this->response()->withHeader('Content-type', 'text/html;charset=utf-8')->write(json_encode($vars,
                256));
        } elseif (is_object($vars)) {
            return $this->response()->write('obj');
        } else {
            return $this->response()->withHeader('Content-type', 'text/html;charset=utf-8')->write($vars);
        }
    }

    protected function isAjax()
    {
        $value = $this->request()->getHeaders()["x-requested-with"] ?? [];
        $value = $value[0] ?? '';
        return 'xmlhttprequest' === strtolower($value);
    }

    protected function successReturn($msg = '操作成功', $data = [])
    {
        return $this->writeJson(200, ['status' => 1, 'data' => $data], $msg);
    }

    protected function errorReturn($msg = '操作失败', $data = [])
    {
        return $this->writeJson(200, ['status' => 0, 'data' => $data], $msg);
    }

    public function onRequest($action): ?bool
    {
        $this->session(new SessionHandler())->start();
        $user = $this->session()->get('user') ?: [];
        $this->view->assign(['user' => $user]);
        $this->user = $user;
        $this->userId = $user['phone'] ?? '';
        $this->param = $this->request()->getRequestParam();
        return true;
        $module = 'Index\Index';
        $controller = 'Sign';
        $validate = "App\\HttpController\\Validate\\" . $module . "\\" . $controller;
        //仅当验证器存在时 进行校验
        if (class_exists($validate)) {
            $validate = new $validate;
            $v = $validate->check($this->request()->getRequestParam());
            /*if ($v->hasScene($scene)) {
                //仅当存在验证场景才校验
                $result = $this->validate($params, $validate . '.' . $scene);
                if (true !== $result) {
                    //校验不通过则直接返回错误信息
                    return json(['code' => 0, 'msg' => $result]);
                }
            }*/
        }
        return true;
        /*return false;
        if (parent::onRequest($action)) {
            if (1) {
                $this->writeJson(Status::CODE_UNAUTHORIZED, '', '登入已过期');
                return false;
            }
            return true;
        }
        return false;*/
    }

    public function index()
    {

    }
}