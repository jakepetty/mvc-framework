<?php
namespace App;

$start = microtime(1);
session_start();

define('DS', '\\');

define('__WEBROOT__', __DIR__);
define('__ROOT__', substr(__DIR__, 0, strlen(__DIR__) - 11));
define('__APP__', __ROOT__ . DS . 'app');
define('__CONTROLLERS__', __APP__ . DS . 'controllers');
define('__MODELS__', __APP__ . DS . 'models');
define('__VIEWS__', __APP__ . DS . 'views');
define('__CONFIGS__', __APP__ . DS . 'config');
define('__LIBS__', __ROOT__ . DS . 'libs');
define('__VENDORS__', __ROOT__ . DS . 'vendor');
define('__UTILITIES__', __LIBS__ . DS . 'utilities');
define('__COMPONENTS__', __LIBS__ . DS . 'components');

require_once __LIBS__ . DS . 'bootstrap.php';
require_once __CONFIGS__ . DS . 'core.php';
if (file_exists(__VENDORS__ . DS . 'autoload.php')) {
    require_once __VENDORS__ . DS . 'autoload.php';
}
require_once __UTILITIES__ . DS . 'globals.php';

class App
{
    public function __construct()
    {
        new Router();
    }
}

new App();

echo sprintf("\n<!-- Execution Time: %s -->\n", number_format(microtime(1) - $start, 5));
