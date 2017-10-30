<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('index');
    }
}
