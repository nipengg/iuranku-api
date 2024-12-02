<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\GroupMember;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupMemberController extends Controller
{
    public function getGroupMembers(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'group_id' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $groupMembers = GroupMember::with(['group', 'member_type', 'user'])->where('id', $request->group_id)->get();

            return ResponseFormatter::success([
                'group_members' => $groupMembers
            ], 'Get Group Member Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }
}
