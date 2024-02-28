<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $data = $request->all();

            $user = User::where('email', $data['email'])->first();

            if ($user->role == 'User') {
                Alert::error('User not Authorized', 'Access Denied');
                return redirect()->route('login')->with('error_message', 'No Privileges. Not Authorized');
            }

            Alert::success('Authorized!', 'Welcome back ' . $user->name);
            return redirect()->route('admin.dashboard');
        }
        Alert::error('Invalid Credential', 'Email or Password might be incorrect.');
        return redirect()->route('login');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('loginPage');
    }
}
