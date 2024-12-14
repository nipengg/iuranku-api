<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\GroupMember;
use App\Models\RequestTuition;
use App\Models\TuitionType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RequestTuitionController extends Controller
{

    public function getRequestTuition(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'period'  => ['required', 'integer'],
                'user_id' => ['nullable', 'integer', 'exists:users,id'],
                'group_id' => ['nullable', 'integer', 'exists:groups,id'],
                'status' => ['nullable', 'string', 'in:Accepted,Rejected,Canceled,Waiting Approval'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => $validator->errors()->all(),
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $period = $request->period;
            // $month = date('m', strtotime($period));
            // $year = date('Y', strtotime($period));

            $query = RequestTuition::with(['member.user', 'member.group'])->whereYear('created_at', $period);

            if ($request->filled('user_id')) {
                $query->whereHas('member', function ($query) use ($request) {
                    $query->where('user_id', $request->user_id);
                });
            }

            if ($request->filled('group_id')) {
                $query->whereHas('member', function ($query) use ($request) {
                    $query->where('group_id', $request->group_id);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Paginate the results
            $data = $query->paginate($request->take ?? 10);

            return ResponseFormatter::success([
                'data' => $data->items(),
                'page' => $data->currentPage(),
                'take' => $data->perPage(),
                'total' => $data->total(),
                'total_page' => ceil($data->total() / $data->perPage()),
            ], 'Get Request Tuition Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => $err->getMessage(),
                'error' => $err->getMessage(),
            ], $err->getMessage(), 500);
        }
    }

    public function storeRequestTuition(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'group_id' => ['required', 'integer', 'exists:groups,id'],
                'file' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
                'nominal' => ['required', 'integer'],
                'remark' => ['required', 'string']
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => $validator->errors()->all(),
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $groupMember = GroupMember::where('user_id', $request->user_id)->where('group_id', $request->group_id)->first();
            if (!$groupMember) {
                throw new Exception("Group Member does not exist.");
            }

            $imagePath = null;
            if ($request->hasFile('file')) {
                $image = $request->file('file');
                $imagePath = $image->store('request_tuition', 'public');
            }

            $requestTuition = RequestTuition::create([
                'member_id' => $groupMember->id,
                'file' => $imagePath,
                'nominal' => $request->nominal,
                'remark' => $request->remark,
                'status' => 'Waiting Approval'
            ]);

            return ResponseFormatter::success([
                'data' => $requestTuition
            ], 'Success Requesting Tuition!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err->getMessage(),
            ], $err->getMessage(), 500);
        }
    }

    public function handleRequestTuition(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'request_tuition_id' => ['required', 'integer', 'exists:request_tuition,id'],
                'status' => ['required', 'string', 'in:Fully Approved,Rejected,Canceled'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $data = $request->all();
            $requestTuition = RequestTuition::findOrFail($request->request_tuition_id);

            if ($requestTuition->status != 'Waiting Approval') {
                return ResponseFormatter::error([
                    'message' => 'Request already responded',
                    'error' => 'Request already responded',
                ], 'Request already responded');
            }

            DB::transaction(function () use ($request, $data, $requestTuition): void {
                $requestTuition->update($data);
            });

            return ResponseFormatter::success([
                'message' => 'Request tuition ' . $request->status
            ], 'Request tuition ' . $request->status);
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => $err->getMessage(),
                'error' => $err->getMessage(),
            ], $err->getMessage(), 500);
        }
    }
}
