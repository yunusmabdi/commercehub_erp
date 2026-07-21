<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pos.login');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);


        if(Auth::attempt($credentials)) {

            $request->session()->regenerate();

            if(auth()->user()->hasRole('Cashier')) {
                return redirect('/pos');
            }

            Auth::logout();

            return back()->withErrors([
                'email'=>'You are not allowed to access POS.'
            ]);
        }


        return back()->withErrors([
            'email'=>'Invalid credentials.'
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('pos.login');
    }
}