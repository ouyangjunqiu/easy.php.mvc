<?php

/**
 * 类加载器
 *
 * @author oShine
 * @since 2017-03-30
 */
class Josloader
{

    /**
     * 自动加载jos类
     * @param $className
     * @return bool
     */
    public static function autoload($className){

        $class_file = __DIR__.DIRECTORY_SEPARATOR."request".DIRECTORY_SEPARATOR.$className.".php";

        if (is_file($class_file)) {
            require_once($class_file);
            if (class_exists($className, false)) {
                return true;
            }
        }

        return false;
    }


}

require_once __DIR__."/JdClient.php";
require_once __DIR__."/RequestCheckUtil.php";
spl_autoload_register(array('Josloader', 'autoload'));
