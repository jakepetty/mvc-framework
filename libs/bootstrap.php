<?php
namespace App;

spl_autoload_register(function ($className) {
    $class = str_replace(__NAMESPACE__ . '\\', null, $className);
    if (file_exists(__UTILITIES__ . DS . strtolower($class) . '.php')) {
        require_once __UTILITIES__ . DS . strtolower($class) . '.php';
    } elseif (file_exists(__LIBS__ . DS . strtolower($class) . '.php')) {
        require_once __LIBS__ . DS . strtolower($class) . '.php';
    } elseif (file_exists(__COMPONENTS__ . DS . strtolower($class) . '.php')) {
        require_once __COMPONENTS__ . DS . strtolower($class) . '.php';
    } elseif (file_exists(__CONTROLLERS__ . DS . $class . '.php')) {
        require_once __CONTROLLERS__ . DS .  $class . '.php';
    } elseif (file_exists(__MODELS__ . DS . $class . '.php')) {
        require_once __MODELS__ . DS . $class . '.php';
    }
});
