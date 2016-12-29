<?php

/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 10/12/16
 * Time: 13:59
 */
namespace giftbox\Factory;
use Illuminate\Database\Capsule\Manager as DB;

class ConnectionFactory extends DB
{
    private static $config;
    private static $db;

    public static function setConfig($file){
        if(file_exists($file)){
            self::$config = parse_ini_file($file);
        }else{
            echo "fichier introuvable<br>";
        }
    }
    public static function makeConnection(){
        self::$db = new DB();
        self::$db->addConnection([
            'driver' => self::$config['driver'],
            'host' => self::$config['host'],
            'database' => self::$config['dbname'],
            'username' => self::$config['username'],
            'password' => self::$config['password'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => ''
        ]);
        self::$db->setAsGlobal();
        self::$db->bootEloquent();
    }

}