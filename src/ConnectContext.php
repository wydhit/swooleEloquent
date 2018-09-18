<?php
/**
 * Created by PhpStorm.
 * User: wyd
 * Date: 2018/9/18
 * Time: 16:06
 */

namespace SwooleEloquent;


use Swoole\Coroutine;

class ConnectContext
{
    private static $context;
    const CONTEXT_TYPE = 'data';

    /**
     * @param string $key
     * @param null $default
     * @return null
     */
    public static function getContextDataByKey(string $key, $default = null)
    {
        $coroutineId = self::getCoroutineId();
        if (isset(self::$context[$coroutineId][self::CONTEXT_TYPE][$key])) {
            return self::$context[$coroutineId][self::CONTEXT_TYPE][$key];
        }
        return $default;
    }

    /**
     * @param string $key
     * @param string $child
     * @param $val
     */
    public static function setContextDataByChildKey(string $key, string $child, $data)
    {
        $coroutineId = self::getCoroutineId();
        self::$context[$coroutineId][self::CONTEXT_TYPE][$key][$child] = $data;
    }

    /**
     * @param string $key
     * @param string $child
     * @param null $default
     * @return null
     */
    public static function getContextDataByChildKey(string $key, string $child, $default = null)
    {
        $coroutineId = self::getCoroutineId();
        if (isset(self::$context[$coroutineId][self::CONTEXT_TYPE][$key][$child])) {
            return self::$context[$coroutineId][self::CONTEXT_TYPE][$key][$child];
        }
        return $default;
    }

    /**
     * @return mixed
     */
    private static function getCoroutineId()
    {
        if (class_exists(Coroutine::class)) {
            return Coroutine::getuid();
        } else {
            return -1;
        }

    }

}
