<?php

/**
 * Json 使用
 * @author oShine
 * @since 2017-03-24
 */
class Json
{
    /**
     * @param $value
     * @param bool $useArray
     * @return mixed
     */
    public static function decode($value,$useArray=true){
        return json_decode($value,$useArray);
    }
    /**
     * @param $value
     * @return string
     */
    public static function encode($value){
        return json_encode($value);
    }

    /**
     * @param $value
     */
    public static function display($value){
        @header('Content-type: text/json;charset=utf-8');
        echo self::encode($value);
    }

    /**
     * @param $data
     * @return array
     */
    public static function format($data){
        if (is_array($data)) {
            foreach ($data as $k => $v)
                $data[$k] = self::format($data[$k]);
        } else settype($data, 'string');

        return $data;
    }

    /**
     * @param $value
     * @return string
     */
    public static function encodeEx($value){
        $value = self::format($value);
        return self::encode($value);
    }

    /**
     * @param $value
     */
    public static function displayEx($value){
        @header('Content-type: text/json;charset=utf-8');
        echo self::encodeEx($value);
    }

}