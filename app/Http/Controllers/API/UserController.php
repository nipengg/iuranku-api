<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUserList(Request $request)
    {
        $data = User::paginate($request->take);

        return ResponseFormatter::success([
            'data' => $data->items(),
            'page' => $data->currentPage(),
            'take' => $data->perPage(),
            'total' => $data->total(),
            'total_page' => ceil($data->total() / $data->perPage()),
        ], 'Success Get User List');
    }

    public function editProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required|max:50',
            'phone' => 'required|max:12|unique:users,phone,' . $request->user_id,
            'address' => 'required|max:250',
            'gender' => 'required',
            'address' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $validator->errors()->all(),
            ], 'Validation Error', 400);
        }

        $user = User::where('id', $request->user_id)->first();

        if ($user == null) {
            return ResponseFormatter::error([
                'message' => 'User not Found',
                'error' => 'User not Found',
            ], 'User not Found', 404);
        }

        try {
            User::where('id', $request->user_id)->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'address' => $request->address,
            ]);

            return ResponseFormatter::success([
                'message' => 'Success Update User Profile!',
            ], 'Success Update User Profile!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }
}
