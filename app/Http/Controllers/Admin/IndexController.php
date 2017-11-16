<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    protected $layout = 'layout.admin';

    public function indexAction()
    {
        return $this->getLayout()->nest('content', 'admin.index');
    }
}
