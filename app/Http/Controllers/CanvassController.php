<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CanvassController extends Controller
{
    public function index()
    {
        return view('canvass.index');
    }
}
