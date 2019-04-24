<?php
namespace App;

use App\View;

class Controller
{
    public function __construct()
    {
        // Load View
        $this->view = new View();
    }
}
