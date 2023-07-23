<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{

    protected $redirectTo = '/profile';


    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed
            return redirect('/tickets');
        }

        return redirect()->back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

}

