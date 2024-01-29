<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use Session;
use Auth;

class AuthController extends Controller
{

    public function login()
    {

        if (Auth::check()) {
            return redirect()->route('home');
        } else {
            return view('Auth.login');
        }
    }
    public function customLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('phone', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('home')
                ->with('success', 'Signed in');
        }
        return redirect()->back()->with('error', 'Phone number or password is incorrect');
    }
    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        } else {
            return view('dashboard.Auth.register');
        }
    }
    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'role' => 'provider',
            'password' => Hash::make($request->password)
        ]);
        auth()->login($user);

        return redirect()->route('home')->withSuccess('You have signed-in');
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
