<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhotosController extends Controller
{
    public function createAction(Request $request)
    {
        $files = $request->files;
        var_dump($request->all(), $files); die;
    }
}
