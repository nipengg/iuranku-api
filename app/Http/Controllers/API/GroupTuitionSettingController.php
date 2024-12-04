<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\GroupTuitionSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupTuitionSettingController extends Controller
{

    public function getGroupTuitionSetting(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'group_tuition_setting_id' => ['required', 'integer', 'exists:group_tuition_setting,id'],
                'tuition_period'  => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            if ($request->status != 'All') {
                $data = GroupTuitionSetting::with(['group', 'typeTuition'])->where('id', $request->group_tuition_setting_id)->where('tuition_period', $request->tuition_period)->paginate($request->take);
            } else {
                $data = GroupTuitionSetting::with(['group', 'typeTuition'])->where('id', $request->group_tuition_setting_id)->paginate($request->take);
            }

            return ResponseFormatter::success([
                'data' => $data->items(),
                'page' => $data->currentPage(),
                'take' => $data->perPage(),
                'total' => $data->total(),
                'total_page' => ceil($data->total() / $data->perPage()),
            ], 'Get Group Tuition Setting Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }


    public function updateGroupTuitionSetting(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'group_tuition_setting_id' => ['required', 'integer', 'exists:group_tuition_setting,id'],
                'tuition_value' => ['required', 'integer'],
                'tuition_period'  => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $data = $request->all();

            $setting = GroupTuitionSetting::findOrFail($request->group_tuition_setting_id);

            DB::transaction(function () use ($request, $data, $setting): void {
                $setting->update($data);
            });

            return ResponseFormatter::success([
                'message' => 'Group Tuition Setting Updated'
            ], 'Group Tuition Setting Updated');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }
}
