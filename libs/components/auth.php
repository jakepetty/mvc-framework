<?php
namespace App;

class Auth
{
    // Setup the user session
    public static function login($data)
    {
        $_SESSION['Auth']['user'] = $data;
    }

    // Allow controller methods
    public static function allow($methods = [])
    {
        // Check if logged in
        if (!Auth::user()) {
            $allowed = false;
            // Check URLs
            foreach ($methods as $method) {
                // index isnt needed so we remove it
                $method = str_replace('.index', null, $method);
                // Check if method is allowed
                if (ltrim($_SERVER['REQUEST_URI'], '/') == str_replace('.', '/', $method)) {
                    $allowed = true;
                }
            }
            // If not allowed redirect to login page
            if (!$allowed) {
                // Set authentication error message
                \App\Flash::message('Access Denied');

                redirect('users.login');
            }
        }
    }

    public static function user($key = null)
    {
        // If logged in
        if (isset($_SESSION['Auth']['user'])) {

            // If key is in array
            if (array_key_exists($key, $_SESSION['Auth']['user'])) {

                // Return key value
                return $_SESSION['Auth']['user'][$key];
            }

            // Return everything
            return $_SESSION['Auth']['user'];
        }

        // If not logged in return false
        return false;
    }

    public static function logout()
    {
        // Destroy the auth session
        unset($_SESSION['Auth']);
    }
}
