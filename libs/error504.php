<?php
use App\Controller;

class Error504 extends Controller
{
    public function index()
    {
        $this->view->render('errors.504');
    }
}
