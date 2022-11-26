<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopPageController extends Controller
{
    public function show()
    {
        return view('top-page');
    }
}
