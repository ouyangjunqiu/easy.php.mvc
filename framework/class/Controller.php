<?php

/**
 * 控制器
 *
 * 可定义常量：
 * CONTROLLER_PARAM:            控制器参数名称
 * CONTROLLER_DIR:                控制器目录
 * CONTROLLER_SEPARATOR:        控制器分组分隔符，分隔符将被目录分隔符替换。
 * CONTROLLER_SUFFIX:            控制器后缀
 * CONTROLLER_EXTENSION:        控制器文件扩展名
 * DEFAULT_CONTROLLER_GROUP:    默认控制器分组
 * DEFAULT_CONTROLLER:            默认控制器
 * ACTION_PARAM:                操作参数名称
 * ACTION_SUFFIX:                操作后缀
 * DEFAULT_ACTION:                默认操作
 * VIEW_DIR:                    视图目录
 * VIEW_EXTENSION:                视图文件扩展名
 *
 * 可使用常量：
 * GROUP_NAME:                    控制器分组名称
 * CONTROLLER_NAME:                控制器名称
 * ACTION_NAME:                    操作名称
 *
 * @author Jack Chan
 * @since 2016-05-08
 */
class Controller
{
    /**
     * 控制器参数名称
     */
    const CONTROLLER_PARAM = 'c';

    /**
     * 控制器目录
     */
    const CONTROLLER_DIR = './app/controller';

    /**
     * 控制器分隔符
     */
    const CONTROLLER_SEPARATOR = '.';

    /**
     * 控制器后缀
     */
    const CONTROLLER_SUFFIX = 'Controller';

    /**
     * 控制器文件扩展名
     */
    const CONTROLLER_EXTENSION = '.php';

    /**
     * 默认控制器分组
     */
    const DEFAULT_CONTROLLER_GROUP = 'default';

    /**
     * 默认控制器
     */
    const DEFAULT_CONTROLLER = 'Index';

    /**
     * 操作参数名称
     */
    const ACTION_PARAM = 'a';

    /**
     * 操作后缀
     */
    const ACTION_SUFFIX = '';

    /**
     * 默认操作
     */
    const DEFAULT_ACTION = 'index';

    /**
     * 视图目录
     */
    const VIEW_DIR = './app/view';

    /**
     * 视图文件扩展名
     */
    const VIEW_EXTENSION = '.phtml';

    /**
     * 控制器名称
     */
    protected $controller;

    /**
     * 操作名称
     */
    protected $action;

    /**
     * 视图参数数组
     */
    protected $params;

    /**
     * 视图
     * @var string $layout
     */
    protected $layout;


    /**
     * 运行控制器
     * @param array $params 参数数组
     */
    public static function runController($params = null)
    {
        // 定义控制器参数名称
        defined('CONTROLLER_PARAM') or define('CONTROLLER_PARAM', self::CONTROLLER_PARAM);

        // 定义控制器目录
        defined('CONTROLLER_DIR') or define('CONTROLLER_DIR', self::CONTROLLER_DIR);

        // 定义控制器分隔符
        defined('CONTROLLER_SEPARATOR') or define('CONTROLLER_SEPARATOR', self::CONTROLLER_SEPARATOR);

        // 定义控制器后缀
        defined('CONTROLLER_SUFFIX') or define('CONTROLLER_SUFFIX', self::CONTROLLER_SUFFIX);

        // 定义控制器后缀
        defined('CONTROLLER_EXTENSION') or define('CONTROLLER_EXTENSION', self::CONTROLLER_EXTENSION);

        // 定义默认控制器分组
        defined('DEFAULT_CONTROLLER_GROUP') or define('DEFAULT_CONTROLLER_GROUP', self::DEFAULT_CONTROLLER_GROUP);

        // 定义默认控制器
        defined('DEFAULT_CONTROLLER') or define('DEFAULT_CONTROLLER', self::DEFAULT_CONTROLLER);

        // 定义操作参数名称
        defined('ACTION_PARAM') or define('ACTION_PARAM', self::ACTION_PARAM);

        // 定义操作后缀
        defined('ACTION_SUFFIX') or define('ACTION_SUFFIX', self::ACTION_SUFFIX);

        // 定义默认操作
        defined('DEFAULT_ACTION') or define('DEFAULT_ACTION', self::DEFAULT_ACTION);

        // 定义视图目录
        defined('VIEW_DIR') or define('VIEW_DIR', self::VIEW_DIR);

        // 定义视图文件扩展名
        defined('VIEW_EXTENSION') or define('VIEW_EXTENSION', self::VIEW_EXTENSION);

        // 初始化控制器名称
        $controller = null;

        // 从传入的参数初始化
        if (is_array($params) && isset($params[CONTROLLER_PARAM]))
            $controller = $params[CONTROLLER_PARAM];
        // 从GPC参数中初始化
        else if (isset($_REQUEST[CONTROLLER_PARAM]))
            $controller = $_REQUEST[CONTROLLER_PARAM];
        // 从查询字符串第一个参数初始化，并且该参数没有等号和值，仅有参数名。
        else if (isset($_SERVER['QUERY_STRING'])) {
            $query = $_SERVER['QUERY_STRING'];
            $a = explode('&', $query);

            if ($a[0])
                $controller = rtrim($a[0],"=");
        }

        if (!$controller)
            $controller = DEFAULT_CONTROLLER;

        // 控制器分组，最后一个元素是控制器名称
        $controllerGroups = explode(CONTROLLER_SEPARATOR, $controller);
        $controllerGroupsLower = array();

        foreach ($controllerGroups as $k => $v) {
            $controllerGroups[$k] = ucfirst($v);
            $controllerGroupsLower[] = lcfirst($v);
        }

        // 控制器路径
        $path = implode(DIRECTORY_SEPARATOR, $controllerGroups);

        // 控制器名称，不包含控制器分组
        $controllerName = $controllerGroups[count($controllerGroups) - 1];

        // 引入控制器类文件
        if (self::requireController($path))
            array_pop($controllerGroupsLower);
        else if (self::requireController($path . DIRECTORY_SEPARATOR . DEFAULT_CONTROLLER))
            $controllerName = DEFAULT_CONTROLLER;
        else if (self::requireController(DEFAULT_CONTROLLER_GROUP . DIRECTORY_SEPARATOR . $path)) {
            array_pop($controllerGroupsLower);
            array_unshift($controllerGroupsLower, DEFAULT_CONTROLLER_GROUP);
        } else die('Controller \'' . $controller . '\' not found.');

        $controllerGroup = implode(CONTROLLER_SEPARATOR, $controllerGroupsLower);
        define('GROUP_NAME', $controllerGroup);
        define('LINK_NAME', $controller);

        // 实例化控制器并运行
        $className = $controllerName . CONTROLLER_SUFFIX;
        $controller = new $className($params);
        $controller->run();
    }

    /**
     * 引入控制器类文件
     * @param string $path
     * @return bool
     */
    protected static function requireController($path)
    {
        $filename = CONTROLLER_DIR . DIRECTORY_SEPARATOR . $path . CONTROLLER_SUFFIX . CONTROLLER_EXTENSION;

        if (!file_exists($filename))
            return false;

        require_once($filename);
        define('VIEW_SUB_DIR', $path);

        return true;
    }

    /**
     * 构造方法
     * @param null|array $params
     */
    public function __construct($params = null)
    {
        $className = get_class($this);
        $this->controller = substr($className, 0, strrpos($className, CONTROLLER_SUFFIX));
        define('CONTROLLER_NAME', $this->controller);

        // 初始化操作名称
        $action = null;

        // 从传入的参数初始化
        if (is_array($params) && isset($params[ACTION_PARAM]))
            $action = $params[ACTION_PARAM];
        // 从GPC参数中初始化
        else if (isset($_REQUEST[ACTION_PARAM]))
            $action = $_REQUEST[ACTION_PARAM];

        if (!$action)
            $action = DEFAULT_ACTION;

        $this->action = $action;
        define('ACTION_NAME', $action);

        // 初始化视图参数数组
        $this->params = is_array($params) ? $params : array();
    }

    /**
     * 运行
     */
    public function run()
    {
        $actionMethod = $this->action . ACTION_SUFFIX;

        $systemAction = array(
            'run',
            'hasParam',
            'getParams',
            'getParam',
            'getParamNoTrim',
            'getParamWithSession',
            'getIntParam',
            'redirect',
            'assign',
            'display',
            'render',
            'output',
            'end'
        );

        if (in_array($actionMethod, $systemAction) || !method_exists($this, $actionMethod))
            die($this->controller . CONTROLLER_SUFFIX . ': action \'' . $this->action . '\' is undefined.');

        $this->$actionMethod();
    }

    /**
     * 是否有POST或GET参数
     * @param string $name
     * @return bool
     */
    public function hasParam($name)
    {
        return isset($_POST[$name]) || isset($_GET[$name]) || isset($_REQUEST[$name]);
    }

    /**
     * 获取POST或GET参数数组
     * @param mixed $names 参数名称列表，数组或逗号分割的字符串。
     * @param boolean $noTrim 不去除首尾空白，默认去除。
     * @return array|null
     */
    public function getParams($names, $noTrim = false)
    {
        if (is_string($names))
            $names = explode(',', $names);

        if (!is_array($names))
            return null;

        $params = array();

        foreach ($names as $name) {
            $name = trim($name);
            if ($this->getParam($name, $noTrim) == null)
                continue;
            $params[$name] = $this->getParam($name, $noTrim);

        }

        return $params;
    }

    /**
     * 获取POST或GET参数
     * @param string $name 参数名称
     * @param boolean $noTrim 不去除首尾空白，默认去除。
     * @return null|string
     */
    public function getParam($name, $noTrim = false)
    {
        if (isset($_POST[$name]))
            return $noTrim || is_array($_POST[$name]) ? $_POST[$name] : trim($_POST[$name]);

        if (isset($_GET[$name]))
            return $noTrim || is_array($_GET[$name]) ? $_GET[$name] : trim($_GET[$name]);

        if (isset($_REQUEST[$name]))
            return $noTrim || is_array($_REQUEST[$name]) ? $_REQUEST[$name] : trim($_REQUEST[$name]);

        return null;
    }

    /**
     *  获取POST或GET参数
     * @param $name
     * @return null|string
     * @author oShine
     */
    public function getParamNoTrim($name)
    {
        return $this->getParam($name, true);
    }

    /**
     * 获取POST或GET参数，无数据的时候取Session中的数据
     * @param $name
     * @param string $sKey
     * @return null|string
     * @author oShine
     */
    public function getParamWithSession($name, $sKey = "")
    {
        if (empty($sKey)) {
            $sKey = GROUP_NAME . "." . LINK_NAME . "." . CONTROLLER_NAME . "." . ACTION_NAME;
        }
        $sKey .= "." . $name;
        $var = $this->getParam($name);
        if (isset($var) && !is_null($var)) {
            @session_start();
            $_SESSION[$sKey] = $var;
            return $var;
        }

        @session_start();
        $var = $_SESSION[$sKey];
        return $var;
    }

    /**
     * 获取POST或GET整型参数
     * @param string $name 参数名称
     * @param boolean $emptyToNull 是否将空值转为null，为方便下拉搜索。
     * @return int|null
     */
    public function getIntParam($name, $emptyToNull = false)
    {
        $value = $this->getParam($name);
        return $emptyToNull && strlen($value) == 0 ? null : intval($value);
    }

    /**
     * 跳转
     * @param string $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        die;
    }

    /**
     * 设置视图参数
     * @param string $name 参数名称
     * @param mixed $value 参数值
     */
    protected function assign($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * @param $msg
     * @param int $code
     * @throws Exception
     */
    public function showError($msg,$code = 0){
        @header('Content-type: text/html;charset=utf-8');
        $this->assign('msg',$msg);
        $this->assign('code',$code);

        extract($this->params);

        $filename = VIEW_DIR . DIRECTORY_SEPARATOR . 'error' . VIEW_EXTENSION;
        if (file_exists($filename)) {
            require_once($filename);
        } else {
            throw new Exception("$filename is not exists");
        }
        exit(-1);
    }

    /**
     * @param $msg
     */
    public function showAlert($msg){
        @header('Content-type: text/html;charset=utf-8');
        echo "<script type='text/javascript'>alert('{$msg}');history.go(-1);</script>";
        exit(-1);
    }


    /**
     * @param string|null $name
     * @return string
     * @throws Exception
     */
    protected function output($name = null)
    {
        extract($this->params);

        $filename = VIEW_DIR . DIRECTORY_SEPARATOR . VIEW_SUB_DIR . DIRECTORY_SEPARATOR . ($name ? $name : $this->action) . VIEW_EXTENSION;

        if (file_exists($filename)) {
            ob_start();
            ob_implicit_flush(false);
            require($filename);
            return ob_get_clean();
        } else {
            throw new Exception("$filename is not exists");
        }
    }


    /**
     * 输出视图
     * @param string $name 视图名称
     */
    protected function display($name = null)
    {
        @header('Content-type: text/html;charset=utf-8');
        extract($this->params);

        $filename = VIEW_DIR . DIRECTORY_SEPARATOR . VIEW_SUB_DIR . DIRECTORY_SEPARATOR . ($name ? $name : $this->action) . VIEW_EXTENSION;

        if (file_exists($filename))
            require_once($filename);
        exit();
    }

    /**
     * 输出带视图的窗口
     * @param null $name
     */
    protected function render($name = null)
    {
        @header('Content-type: text/html;charset=utf-8');
        if ($this->layout) {
            $content = $this->output($name);
            $this->params["content"] = $content;
            extract($this->params);

            $filename = VIEW_DIR . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, explode(".", $this->layout)) . VIEW_EXTENSION;
            if (file_exists($filename))
                require($filename);
        } else
            $this->display($name);
        exit();
    }

    /**
     * @param int $number
     */
    protected function end($number = 0){
        @header('Content-type: text/html;charset=utf-8');
        settype($number,"string");
        echo $number;
        exit();
    }
}