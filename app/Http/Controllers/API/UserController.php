<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserList(Request $request)
    {
        $data = User::paginate($request->take);

        return ResponseFormatter::success($data, 'Success Get User List');
    }
}
