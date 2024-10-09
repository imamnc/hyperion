<?php

namespace Itpi\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Itpi\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        Mail::fake();
        return view('panel.home.index');
    }
}
