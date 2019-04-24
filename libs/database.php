<?php
namespace App;

use PDO;

class Database
{
    private static $instance = NULL;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            try {
                switch (config('database.engine')) {
                    case 'sqlite':
                        $file = __ROOT__ . DS . config('database.filename', 'database.sqlite');
                        if (!file_exists($file)) {
                            touch($file);
                        }
                        self::$instance = new pdo(
                            'sqlite:' . __ROOT__ . DS . config('database.filename', 'database.sqlite')
                        );
                        break;
                    case 'mysql':
                        self::$instance = new pdo(
                            'mysql:host=' . config('database.host') . ';dbname=' . config('database.database') . ';charset=utf8',
                            config('database.username'),
                            config('database.password'),
                            $pdo_options
                        );
                        break;
                }
            } catch (PDOException $ex) {
                die(json_encode(array('outcome' => false, 'message' => 'Unable to connect')));
            }
        }
        return self::$instance;
    }
}
