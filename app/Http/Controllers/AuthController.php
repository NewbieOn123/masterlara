<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   public function viewlogin()
   {
        // dd('tekan');
        return view('layouts.auth.login');
   }
   
   public function loginaction(Request $request)
    {
        dd($request);
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $ceklogin = $request->only('email', 'password');
        
        if (Auth::attempt($ceklogin)) {
            return redirect()->intended('dashboard')->withSuccess('Signed in');
        }
        
        return redirect("login")->withSuccess('Login details are not valid');
    }

    public function viewregister()
    {
            return view('layouts.auth.register');
    }

    public function registeraction(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'

        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => 'Y',
        ]);

        return redirect('layouts.auth.login')->withSuccess('You have signed-in');
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return redirect('/');
        // dd('sek');
        // return redirect('layouts.auth.login')->withSuccess('You Success Logout');

        // return view('layouts.auth.login');
    }

}
