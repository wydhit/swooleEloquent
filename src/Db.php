<?php
/**
 * Created by PhpStorm.
 * User: wyd
 * Date: 2018/9/14
 * Time: 15:46
 */

namespace SwooleEloquent;


use Illuminate\Container\Container;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Fluent;

class Db
{
    private static $dataConfig = [
        'default' => 'default',
        'fetch' => \PDO::FETCH_OBJ,
        'connections' => [
            'default' => [
                'driver' => 'mysql',
                'host' => '10.0.13',
                'port' => '3306',
                'database' => 'youbeitmp',
                'username' => 'root',
                'password' => 'Bfbjsq@2018',
                'unix_socket' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => 'yb_',
                'strict' => true,
                'engine' => null,
            ],
            'default2' => [
                'driver' => 'mysql',
                'host' => '10.0.13',
                'port' => '3306',
                'database' => 'youbeitmp',
                'username' => 'root',
                'password' => 'Bfbjsq@2018',
                'unix_socket' => false,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => 'yb_',
                'strict' => true,
                'engine' => null,
            ]
        ]
    ];

    public static function init($config = [])
    {
        self::$dataConfig = array_merge_recursive(self::$dataConfig, $config);
    }

    public static function table($table = "", $connect_name = 'default')
    {
        return self::instance()->getConnection($connect_name)->table($table);
    }

    public static function select($query, $bindings = [], $useReadPdo = true)
    {
        return self::instance()->getConnection()->select($query, $bindings, $useReadPdo);
    }

    public static function insert($query, $bindings = [])
    {
        return self::instance()->getConnection()->insert($query, $bindings);
    }

    public static function update($query, $bindings = [])
    {
        return self::instance()->getConnection()->update($query, $bindings);
    }

    public static function delete($query, $bindings = [])
    {
        return self::instance()->getConnection()->delete($query, $bindings);
    }

    public static function listen(\Closure $callback)
    {
        return self::instance()->getConnection()->listen($callback);
    }

    public static function transaction(\Closure $callback, $attempts = 1)
    {
        return self::instance()->getConnection()->transaction($callback, $attempts);
    }

    public static function beginTransaction()
    {
        return self::instance()->getConnection()->beginTransaction();
    }

    public static function rollBack()
    {
        return self::instance()->getConnection()->rollBack();
    }

    public static function commit()
    {
        return self::instance()->getConnection()->commit();
    }

    public static function connection($connect_name = 'default')
    {
        return self::instance()->getConnection($connect_name);
    }

    public static function disConnection()
    {
        /**
         * @var $contextDataByKey Connection
         */
        $contexts = ConnectContext::getContextDataByKey('connect');
        if (is_array($contexts)) {
            foreach ($contexts as $context) {
                if (method_exists($context, 'disconnect')) {
                    $context->disconnect();
                }
            }
        } else {
            if (method_exists($contexts, 'disconnect')) {
                $contexts->disconnect();
            }
        }

    }

    public static function schema($connect_name = null)
    {
        return self::instance()->getConnection($connect_name)->getSchemaBuilder();
    }

    public static function instance()
    {
        return new static();
    }

    /**
     * laravel容器
     * @var \Illuminate\Contracts\Container\Container|\Illuminate\Foundation\Application
     */
    protected $container;
    /**
     * 数据库管理实例
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $manager;

    public function __construct(Container $container = null)
    {
        $this->container = $container ?: new Container();
        $config = new Fluent();
        $config['database.connections'] = self::$dataConfig['connections'];
        $config['database.fetch'] = self::$dataConfig['fetch'];
        $config['database.default'] = self::$dataConfig['default'];
        $this->container->instance('config', $config);
        $factory = new ConnectionFactory($this->container);
        $this->manager = new DatabaseManager($this->container, $factory);
    }


    /**
     * @param $connect_name
     * @return \Illuminate\Database\Connection
     */
    public function getConnection($connect_name = 'default')
    {
        $connect = ConnectContext::getContextDataByChildKey('connect', $connect_name);
        if (!$connect instanceof Connection) {
            $connect = $this->manager->connection($connect_name);
            ConnectContext::setContextDataByChildKey('connect', $connect_name, $connect);
        }
        return $connect;
    }

    public static function __callStatic($method, $parameters)
    {
        return static::connection()->$method(...$parameters);
    }

}
