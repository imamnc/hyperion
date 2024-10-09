<?php

namespace Itpi\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Itpi\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function logout()
    {
        // Logout
        Auth::logout();
        // Redirect
        return redirect()->route('login');
    }
}
