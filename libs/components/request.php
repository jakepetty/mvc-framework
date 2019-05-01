<?php
namespace App;

class Request
{
    // Create a structured way to access $_POST
    public static function all()
    {
        if (isset($_POST['password'])) {
            $_POST['password'] = sha1(config('security.salt') . $_POST['password']);
        }
        if (isset($_POST['confirm_password'])) {
            $_POST['confirm_password'] = sha1(config('security.salt') . $_POST['confirm_password']);
        }
        return $_POST;
    }

    // Return uploaded files if any
    public static function file($key = null)
    {
        if ($key) {
            if (array_key_exists($key, $_FILES)) {
                return $_FILES[$key];
            }
            return false;
        }
        return $_FILES;
    }
}
