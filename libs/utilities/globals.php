<?php
// Debug
function debug($var = null)
{
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

// Debug then die
function dd($var = null)
{
    debug($var);
    exit();
}

// Redirect to a route
function redirect($url)
{
    $url = str_replace('.', '/', $url);
    header('Location: ' . config('app.url') . '/' . $url);
    exit();
}

// Redirect back to referal page
function back($url = null)
{
    $ref = @$_SERVER['HTTP_REFERER'];
    header('Location: ' . $ref ? $ref : config('app.url'));
    exit();
}

// Grab items from config files
function config($key = null, $default = '')
{
    if ($key) {
        list($key, $field) = explode('.', $key);
        if (array_key_exists($key, $_ENV)) {
            if (array_key_exists($field, $_ENV[$key])) {
                return $_ENV[$key][$field];
            } else {
                return $default;
            }
        }else{
            return;
        }
    }
    return $_ENV;
}

// Generate url based off route path
function route()
{
    $args = func_get_args();
    if ($args[0] == 'home.index') {
        return config('app.url');
    }
    $url = '/' . str_replace('/index', null, str_replace('.', '/', $args[0]));
    if (isset($args[1])) {
        $url .= '/' . $args[1];
    }
    return config('app.url') . $url;
}

// Cache proof assets
function asset($path)
{
    $cache = filemtime(__WEBROOT__ . $path);

    return $path . '?' . $cache;
}
