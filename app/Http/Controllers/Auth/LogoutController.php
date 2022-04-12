<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    //logout
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/admin');
        // Auth::logout();
        // Session::flush();
        // return redirect('/');
    }
}
