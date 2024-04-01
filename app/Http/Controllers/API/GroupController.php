<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function getGroup(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $user = User::where('id', $request->user_id)
                        ->where('deleted_at', null)
                        ->first();

            if ($user == null) {
                return ResponseFormatter::error([
                    'message' => 'User not Found',
                    'error' => 'User not Found',
                ], 'User not Found', 404);
            }

            $user->load(['group_member' => function ($query) {
                $query->where('status', 'Active');
                $query->where('deleted_at', null);
            }]);

            return ResponseFormatter::success([
                'groups' => $user->group_member->load('group', 'member_type'),
            ], 'Get Group Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }
}
