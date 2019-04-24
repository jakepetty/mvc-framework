<?php
use App\AppController;

class HomeController extends AppController
{
    public function index()
    {
        $this->view->render('home.index');
    }
}
