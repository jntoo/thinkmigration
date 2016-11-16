<?php
/**
 * Created by PhpStorm.
 * User: JnToo
 * Date: 2016/11/4
 * Time: 12:48
 */

namespace Laravel;

use think\Config;
use Laravel\Container\Container;
use Laravel\Connectors\ConnectionFactory;
use Laravel\Connection\Connection;
use Laravel\Connection\ConnectionResolver;

class App{
    /**
     * @var array
     */
    static public $apps = array();
    static private function init()
    {
        if(!self::$apps){
            import('Laravel\\Support\\helpers');
            self::initConnection();
        }
    }
    static private function initConnection()
    {
        // 初始化连接
        $default =  [
            'driver' => 'mysql',
            'collation' => 'utf8_general_ci',
            'strict' => true,
            'engine' => null,
        ];
        // 新建数据库
        $config = Config::get('database');
        $config['driver'] = $config['type'];
        $config['host'] = $config['hostname'];
        foreach($default as $k=>$v ){
            if(empty($config[$k])){
                $config[$k] = $v;
            }
        }

        $ConnectionFactory = new ConnectionFactory(new Container);
        $connection = $ConnectionFactory->make($config);
        self::$apps['db'] = &$connection;
        $ConnectionResolver = new ConnectionResolver();
        $ConnectionResolver->addConnection('default' , $connection);
        $ConnectionResolver->setDefaultConnection('default');
        self::$apps['resolver'] = &$ConnectionResolver;
    }

    /**
     * @return \Laravel\Connection\Connection
     */
    static public function db()
    {
        self::init();
        return self::$apps['db'];
    }

    static public function resolver()
    {
        self::init();
        return self::$apps['resolver'];
    }

}

