<?php

namespace App\Http\Controllers\API;

use App\Enum\Constant;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\GroupApplication;
use App\Models\GroupMember;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupApplicationController extends Controller
{
    public function getGroupApplication(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'group_id' => ['required', 'integer'],
                'status' => ['required', 'string', 'in:Pending,Accepted,Rejected,Canceled,All'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            if ($request->status != 'All') {
                $data = GroupApplication::with(['group', 'user'])->where('group_id', $request->group_id)->where('status', $request->status)->paginate($request->take);
            } else {
                $data = GroupApplication::with(['group', 'user'])->where('group_id', $request->group_id)->paginate($request->take);
            }

            return ResponseFormatter::success([
                'data' => $data->items(),
                'page' => $data->currentPage(),
                'take' => $data->perPage(),
                'total' => $data->total(),
                'total_page' => ceil($data->total() / $data->perPage()),
            ], 'Get Group Application Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }

    public function inviteUserGroupApplication(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'group_id' => ['required', 'integer', 'exists:groups,id'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $groupApplication = GroupApplication::create([
                'user_id' => $request->user_id,
                'group_id' => $request->group_id,
                'status' => 'Pending'
            ]);

            return ResponseFormatter::success([
                'data' => $groupApplication
            ], 'Success Invite User!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }

    public function handleGroupApplicationResponse(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'group_application_id' => ['required', 'integer', 'exists:group_application,id'],
                'status' => ['required', 'string', 'in:Accepted,Rejected,Canceled'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $data = $request->all();

            $application = GroupApplication::findOrFail($request->group_application_id);

            if ($application->status != 'Pending') {
                return ResponseFormatter::success([
                    'message' => 'Group Application already responded'
                ], 'Group Application already responded');
            }

            DB::transaction(function () use ($request, $data, $application): void {
                $application->update($data);

                if ($request->status == 'Accepted') {
                    GroupMember::create([
                        'user_id' => $application->user_id,
                        'group_id' => $application->group_id,
                        'member_type_id' => Constant::MEMBER_TYPE['Group Member'],
                        'status' => 'Active',
                        'join_date' => Carbon::now(),
                        'leave_date' => null,
                        'leave_note' => null,
                    ]);
                }
            });

            return ResponseFormatter::success([
                'message' => 'Group Application ' . $request->status
            ], 'Group Application ' . $request->status);
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }
}
