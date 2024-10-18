<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'role' => 'Admin'])) {
            $data = $request->all();
            $user = User::where('email', $data['email'])->first();
            Alert::success('Authorized!', 'Welcome back ' . $user->name);
            return redirect()->route('admin.dashboard');
        }
        Alert::error('Invalid Credential or Unauthorized', 'Email or Password might be incorrect. Access Denied');
        return redirect()->route('loginPage')->withInput();
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('loginPage');
    }

    public function verifyEmail($id, $hash)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            if (!$user->hasVerifiedEmail() && hash_equals(sha1($user->getEmailForVerification()), (string)$hash)) {
                $user->markEmailAsVerified();
                event(new Verified($user));
            }
        }
    }
}
