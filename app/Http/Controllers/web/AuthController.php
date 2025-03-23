<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login_form()
    {   
        if(Auth::user()) return redirect('admin.dashboard');
        return Inertia::render('auth/Login');
    }

    public function login(LoginRequest $request){

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard',[],true));

    }
}
