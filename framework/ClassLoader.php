<?php
/**
 * 类加载器
 * 
 * 可定义常量：
 * CLASS_DIR:					类库目录
 * MODEL_DIR:					模型目录
 * MODEL_SUFFIX:				模型后缀
 * CLASS_EXTENSION:				类文件扩展名
 * CLASS_SEPARATOR:				类包分隔符
 * 
 * @author Jack Chan
 * @since 2016-06-05
 */
class ClassLoader
{
	/**
	 * 类库目录
	 */
	const CLASS_DIR				= './framework/class';

    /**
     * 类库目录
     */
    const CLASS_FRAMEWORK_DIR				= './framework';

    /**
     * app目录
     */
    const APP_CLASS_DIR				= './app';
	
	/**
	 * 模型目录
	 */
	const MODEL_DIR				= './app/model';
	
	/**
	 * 模型后缀
	 */
	const MODEL_SUFFIX			= 'Model';

	
	/**
	 * 类文件扩展名
	 */
	const CLASS_EXTENSION		= '.php';
	
	/**
	 * 类包分隔符
	 */
	const CLASS_SEPARATOR		= '_';
	
	/**
	 * 初始化
	 */
	public static function init()
	{
		// 定义类库目录
		defined('CLASS_DIR') or define('CLASS_DIR', self::CLASS_DIR);

		// 定义模型目录
		defined('MODEL_DIR') or define('MODEL_DIR', self::MODEL_DIR);
		
		// 定义模型后缀
		defined('MODEL_SUFFIX') or define('MODEL_SUFFIX', self::MODEL_SUFFIX);
		
		// 类文件扩展名
		defined('CLASS_EXTENSION') or define('CLASS_EXTENSION', self::CLASS_EXTENSION);
		
		// 类包分隔符
		defined('CLASS_SEPARATOR') or define('CLASS_SEPARATOR', self::CLASS_SEPARATOR);

        spl_autoload_register(array('ClassLoader', 'loadFramework'));
        spl_autoload_register(array('ClassLoader', 'loadApp'));
		spl_autoload_register(array('ClassLoader', 'autoload'));
	}
	
	/**
	 * 自动加载类
     * @param string $className
     * @return bool
	 */
	public static function autoload($className)
	{
		if (($modelPos = strrpos($className, MODEL_SUFFIX)) && $modelPos + strlen(MODEL_SUFFIX) == strlen($className))
			$dir = MODEL_DIR;
        else
			$dir = CLASS_DIR;
		
		$filename = $dir.DIRECTORY_SEPARATOR.str_replace(CLASS_SEPARATOR, DIRECTORY_SEPARATOR, $className).CLASS_EXTENSION;
		
		if (file_exists($filename)) {
			require($filename);
            return true;
        }
        return false;

	}

    /**
     * 自动加载Framework类
     * @param $className
     * @return bool
     */
    public static function loadFramework($className){
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        if (strpos($className, 'system\\') === 0) {
            $class_file = self::CLASS_FRAMEWORK_DIR . substr($class_path, strlen('system')) . '.php';
            if (is_file($class_file)) {
				require($class_file);
                if (class_exists($className, false)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 自动加载app类
     * @param $className
     * @return bool
     */
	public static function loadApp($className){
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        if (strpos($className, 'app\\') === 0) {
            $class_file = self::APP_CLASS_DIR . substr($class_path, strlen('app')) . '.php';
            if (is_file($class_file)) {
                require($class_file);
                if (class_exists($className, false)) {
                    return true;
                }
            }
        }
        return false;
    }


}
require_once __DIR__."/lib/phpqrcode/qrlib.php";
require_once __DIR__."/lib/Excel/reader.php";
require_once __DIR__."/lib/simple_html_dom/simple_html_dom.php";

ClassLoader::init();