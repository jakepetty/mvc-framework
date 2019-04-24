<?php
namespace App;

class Flash
{
    // Set a variable inside of flash session
    public static function set($key, $val)
    {
        $_SESSION['Flash'][$key] = $val;
    }
    // Setup flash message
    public static function message($message, $class = 'danger')
    {
        $_SESSION['Flash'] = compact('message', 'class');
    }
    // Show flash message
    public static function show()
    {
        if (isset($_SESSION['Flash'])) {
            $html =  sprintf('<div class="alert alert-%s">%s</div>', $_SESSION['Flash']['class'], $_SESSION['Flash']['message']);

            // Remove flash message
            unset($_SESSION['Flash']);
            return $html;
        }
        return null;
    }
}
