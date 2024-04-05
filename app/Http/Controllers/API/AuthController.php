<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Rules\Password;

class AuthController extends Controller
{
    public function googleOAuth(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'email' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $user = User::where('email', $request->email)->first();

            // If user not exist
            if (!$user) {
                return ResponseFormatter::success([
                    'user' => [
                        'name' => $request->name,
                        'email' => $request->email
                    ],
                ], 'Register');
            }

            $tokenResult = $user->createToken('authToken', ['*'], now()->addMinutes(20))->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Authenticated');

        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:4'],
                'address' => ['required'],
                'gender' => ['required'],
                'phone' => ['nullable', 'string', 'max:12'],
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->email,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'User'
            ]);

            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken', ['*'], now()->addMinutes(20))->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'User Registered');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Authentication Failed', 500);
        }
    }

    public function login(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string'],
                'password' => ['required', 'string'],
                'remember' => ['required'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (!Auth::attempt($credentials, $request->remember)) {
                return ResponseFormatter::error([
                    'message' => 'Email or password maybe incorrect.',
                ], 'Invalid Credential', 400);
            }

            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken', ['*'], now()->addMinutes(20))->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Authenticated');

        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong...',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }

    public function fetch(Request $request)
    {
        $user = $request->user();
        $user->makeHidden('groups');

        return ResponseFormatter::success([
            'user' => $user,
            'groups' => $user->groups,
        ], 'Authenticated');
    }
}
