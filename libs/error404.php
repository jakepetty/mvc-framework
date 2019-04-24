<?php
use App\Controller;

class Error404 extends Controller
{
    public function index()
    {
        $this->view->render('errors.404');
    }
}
