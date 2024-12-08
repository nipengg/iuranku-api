<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\GroupTuitionSetting;
use App\Models\TuitionType;
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
                'group_id' => ['required', 'integer', 'exists:groups,id'],
                'type_tuition' => ['required', 'string', 'in:Kebersihan,Keamanan,Kematian'],
                'tuition_period'  => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $type = TuitionType::where('tuition_name', $request->type_tuition)->first();

            if (!$type) {
                throw new Exception("Invalid tuition type.");
            }

            $data = GroupTuitionSetting::with(['group', 'typeTuition'])->where('group_id', $request->group_id)->where('type_tuition_id', $type->id)->where('tuition_period', $request->tuition_period)->first();

            return ResponseFormatter::success([
                'data' => $data,
            ], 'Get Group Tuition Setting Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }

    public function insertOrUpdateGroupTuitionSetting(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'group_tuition_setting_id' => ['nullable', 'integer', 'exists:group_tuition_setting,id'],
                'group_id' => ['nullable', 'integer', 'exists:groups,id'],
                'type_tuition' => ['nullable', 'string', 'in:Kebersihan,Keamanan,Kematian'],
                'tuition_value' => ['required', 'integer'],
                'tuition_period' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Validation failed.',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $data = $request->only(['group_id', 'tuition_value', 'tuition_period']);
            $groupTuitionSettingId = $request->group_tuition_setting_id;

            DB::transaction(function () use ($request, $groupTuitionSettingId, $data): void {
                if ($groupTuitionSettingId) {
                    $setting = GroupTuitionSetting::findOrFail($groupTuitionSettingId);
                    $setting->update($data);
                } else {
                    $type = TuitionType::where('tuition_name', $request->type_tuition)->first();

                    if ($type) {
                        $data['type_tuition_id'] = $type->id;
                    } else {
                        throw new Exception("Invalid tuition type.");
                    }

                    $existingSetting = GroupTuitionSetting::where('group_id', $request->group_id)
                        ->where('type_tuition_id', $data['type_tuition_id'])
                        ->where('tuition_period', $request->tuition_period)
                        ->first();

                    if ($existingSetting) {
                        throw new Exception("A tuition setting for this group, type, and year already exists.");
                    }

                    GroupTuitionSetting::create($data);
                }
            });

            return ResponseFormatter::success([
                'message' => $groupTuitionSettingId
                    ? 'Group Tuition Setting Updated'
                    : 'Group Tuition Setting Created',
            ], $groupTuitionSettingId
                ? 'Group Tuition Setting Updated'
                : 'Group Tuition Setting Created');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong.',
                'error' => $err->getMessage(),
            ], 'Error', 500);
        }
    }
}
