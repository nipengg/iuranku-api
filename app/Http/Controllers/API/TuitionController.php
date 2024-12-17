<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\GroupMember;
use App\Models\GroupTuitionSetting;
use App\Models\Tuition;
use App\Models\TuitionType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TuitionController extends Controller
{
    public function getTuitionByMemberId(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'member_id' => ['required', 'integer', 'exists:group_members,id'],
                'period' => ['required', 'integer'],
                'type_tuition' => ['nullable', 'string', 'in:Kebersihan,Keamanan,Kematian'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => $validator->errors()->all(),
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $period = $request->period;

            $query = Tuition::with(['member.user', 'member.group', 'typeTuition', 'requestTuition'])->where('member_id', $request->member_id)->whereYear('period', $period);

            if ($request->filled('type_tuition')) {
                $type = TuitionType::where('tuition_name', $request->type_tuition)->first();
                $query->where('type_tuition_id', $type->id);
            }

            $data = $query->get();

            return ResponseFormatter::success([
                'data' => $data,
            ], 'Get Tuition Member Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => $err->getMessage(),
                'error' => $err->getMessage(),
            ], $err->getMessage(), 500);
        }
    }

    public function storeTuition(Request $request)
    {
        try {
            $data = $request->all();

            if (!is_array($data) || empty($data)) {
                return ResponseFormatter::error([
                    'message' => 'Payload must be a non-empty array of tuition data.',
                    'error' => 'Invalid Payload',
                ], 'Validation Error', 400);
            }
    
            $successes = [];
            $errors = [];
    
            DB::transaction(function () use ($data, &$successes, &$errors): void {
                foreach ($data as $index => $tuitionData) {
                    $validator = Validator::make($tuitionData, [
                        'request_tuition_id' => ['required', 'integer', 'exists:request_tuition,id'],
                        'member_id' => ['required', 'integer', 'exists:group_members,id'],
                        'type_tuition' => ['required', 'string', 'in:Kebersihan,Keamanan,Kematian'],
                        'nominal' => ['required', 'integer'],
                        'period' => ['required', 'date']
                    ]);
    
                    if ($validator->fails()) {
                        $errors[] = [
                            'index' => $index,
                            'message' => $validator->errors()->all(),
                        ];
                        continue;
                    }
    
                    $period = $tuitionData['period'];
                    $month = date('m', strtotime($period));
                    $year = date('Y', strtotime($period));
    
                    $member = GroupMember::find($tuitionData['member_id']);
                    $type = TuitionType::where('tuition_name', $tuitionData['type_tuition'])->first();
    
                    if (!$member || !$type) {
                        $errors[] = [
                            'index' => $index,
                            'message' => 'Invalid member or tuition type.',
                        ];
                        continue;
                    }
    
                    $tuitionSetting = GroupTuitionSetting::where('group_id', $member->group_id)
                        ->where('type_tuition_id', $type->id)
                        ->where('tuition_period', $year)
                        ->first();
    
                    $tuitionPaid = Tuition::where('member_id', $tuitionData['member_id'])
                        ->where('type_tuition_id', $type->id)
                        ->whereYear('period', $year)
                        ->whereMonth('period', $month)
                        ->get();
    
                    $paidAmount = 0;
                    foreach ($tuitionPaid as $tuition) {
                        $paidAmount += $tuition->nominal;
                    }
    
                    if ($paidAmount >= $tuitionSetting->tuition_value) {
                        $errors[] = [
                            'index' => $index,
                            'message' => 'Tuition already been paid for ' . date('F', strtotime($period)) . ' ' . $year,
                        ];
                        continue;
                    }
    
                    $tuition = Tuition::create([
                        'request_tuition_id' => $tuitionData['request_tuition_id'],
                        'member_id' => $tuitionData['member_id'],
                        'type_tuition_id' => $type->id,
                        'nominal' => $tuitionData['nominal'],
                        'period' => $tuitionData['period'],
                    ]);
    
                    $successes[] = $tuition;
                }
            });
    
            return ResponseFormatter::success([
                'data' => [
                    'successes' => $successes,
                    'errors' => $errors,
                ],
            ], 'Tuition processing completed.');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err->getMessage(),
            ], $err->getMessage(), 500);
        }
    }
    
    
    public function storeTuitionOld(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'request_tuition_id' => ['required', 'integer', 'exists:request_tuition,id'],
                'member_id' => ['required', 'integer', 'exists:group_members,id'],
                'type_tuition' => ['required', 'string', 'in:Kebersihan,Keamanan,Kematian'],
                'nominal' => ['required', 'integer'],
                'period' => ['required', 'date']
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => $validator->errors()->all(),
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $period = $request->period;
            $month = date('m', strtotime($period));
            $year = date('Y', strtotime($period));

            $member = GroupMember::where('id', $request->member_id)->first();
            $type = TuitionType::where('tuition_name', $request->type_tuition)->first();
            
            $tuitionSetting = GroupTuitionSetting::where('group_id', $member->group_id)
                ->where('type_tuition_id', $type->id)
                ->where('tuition_period', $year)
                ->first();

            $tuitionPaid = Tuition::where('member_id', $request->member_id)
                ->where('type_tuition_id', $type->id)
                ->whereYear('period', $year)
                ->whereMonth('period', $month)
                ->get();

            $paidAmount = 0;
            foreach ($tuitionPaid as $tuition) {
                $paidAmount += $tuition->nominal;
            }

            if ($paidAmount >= $tuitionSetting->tuition_value) {
                return ResponseFormatter::error([
                    'message' => 'Tuition already been paid',
                    'error' => 'Tuition already been paid for ' . date('F', strtotime($period)) . ' ' . $year,
                ], 'Tuition already been paid', 400);
            }

            $tuition = Tuition::create([
                'request_tuition_id' => $request->request_tuition_id,
                'member_id' => $request->member_id,
                'type_tuition_id' => $type->id,
                'nominal' => $request->nominal,
                'period' => $request->period,
            ]);

            return ResponseFormatter::success([
                'data' => $tuition
            ], 'Success saving tuition!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err->getMessage(),
            ], $err->getMessage(), 500);
        }
    }
}
