<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\GroupMember;
use Carbon\Carbon;
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
                'status' => ['required', 'string', 'in:Active,Inactive,All'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            if ($request->status != 'All') {
                $data = GroupMember::with(['group', 'member_type', 'user'])->where('id', $request->group_id)->where('status', $request->status)->paginate($request->take);
            } else {
                $data = GroupMember::with(['group', 'member_type', 'user'])->where('id', $request->group_id)->paginate($request->take);
            }

            return ResponseFormatter::success([
                'data' => $data->items(),
                'page' => $data->currentPage(),
                'take' => $data->perPage(),
                'total' => $data->total(),
                'total_page' => ceil($data->total() / $data->perPage()),
            ], 'Get Group Member Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }

    public function leaveGroup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'group_member_id' => ['required', 'integer', 'exists:group_members,id'],
                'leave_note' => ['required', 'string']
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $data = $request->all();
            $data['leave_date'] = Carbon::now();
            $data['status'] = "Inactive";
            $groupMember = GroupMember::findOrFail($data['group_member_id']);

            $groupMember->update($data);

            return ResponseFormatter::success([
                'messages' => 'Leave Group Saved'
            ], 'Leave Group Saved');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }
}
