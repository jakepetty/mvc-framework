<?php
namespace App;

class Router
{
    protected $flag = 0;
    public function __construct()
    {
        // Router
        $page = rtrim($_SERVER['REQUEST_URI'], '/');
        $tokens = explode('/', $page);
        array_shift($tokens);
        if (empty($tokens)) {
            $tokens[0] = 'home';
            $tokens[1] = 'index';
        }
        $this->route($tokens);
        if ($this->flag) {
            switch ($this->flag) {
                case 1:
                    $controllerName = 'Error404';
                    break;
                case 2:
                    $controllerName = 'Error504';
                    break;
            }
            // Error Page
            $controller = new $controllerName();
            $controller->index();
        }
    }

    private function route($tokens)
    {

        // Dispatcher
        $class = ucfirst(array_shift($tokens));
        $class = $class . 'Controller';
        $controllerName = sprintf(__CONTROLLERS__ . DS . "%s.php", $class);
        if (file_exists($controllerName)) {
            $controller = new $class();
            // Action
            if (!empty($tokens)) {
                $method = array_shift($tokens);
                if (method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], $tokens);
                } else {
                    // Method not found
                    $this->flag = 2;
                }
            } else {
                // Default method index
                $controller->index($tokens);
            }
        } else {
            // Error Page
            $this->flag = 1;
        }
    }
}
