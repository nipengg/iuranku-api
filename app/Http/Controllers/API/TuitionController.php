<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\GroupMember;
use App\Models\GroupTuitionSetting;
use App\Models\Tuition;
use App\Models\TuitionType;
use Carbon\Carbon;
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


    public function getTuitionMember(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'group_id' => ['required', 'integer', 'exists:groups,id'],
                'period' => ['required', 'integer'],
                'type_tuition' => ['required', 'string', 'in:Kebersihan,Keamanan,Kematian'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => $validator->errors()->all(),
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $year = $request->input('period');
            $groupId = $request->input('group_id');

            $type = TuitionType::where('tuition_name', $request->type_tuition)->first();

            $dataPaginated = GroupMember::with('user', 'group', 'member_type')
                ->where('group_id', $groupId)
                ->paginate($request->take ?? 10);

            $data = collect($dataPaginated->items());

            $tuitionStatus = $data->map(function ($member) use ($year, $type) {
                $monthlyStatus = [];
                $tuitionSettings = GroupTuitionSetting::where('group_id', $member->group_id)
                    ->where('type_tuition_id', $type->id)
                    ->where('tuition_period', $year)
                    ->first();

                // If no tuition settings are found, set all months to false
                if (!$tuitionSettings) {
                    foreach (range(1, 12) as $month) {
                        $monthlyStatus[$month] = false;
                    }

                    return [
                        'member' => $member,
                        'monthlyStatus' => $monthlyStatus,
                    ];
                }

                foreach (range(1, 12) as $month) {
                    $monthPaid = Tuition::where('member_id', $member->id)
                        ->where('type_tuition_id', $type->id)
                        ->whereYear('period', $year)
                        ->whereMonth('period', $month)
                        ->sum('nominal');

                    $monthlyStatus[$month] = $monthPaid >= $tuitionSettings->tuition_value;
                }

                return [
                    'member' => $member,
                    'monthlyStatus' => $monthlyStatus,
                ];
            });

            return ResponseFormatter::success([
                'data' => $tuitionStatus,
                'page' => $dataPaginated->currentPage(),
                'take' => $dataPaginated->perPage(),
                'total' => $dataPaginated->total(),
                'total_page' => ceil($dataPaginated->total() / $dataPaginated->perPage()),
            ], 'Tuition processing completed.');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err->getMessage(),
            ], $err->getMessage(), 500);
        }
    }

    public function getTuitionMemberDetail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'member_id' => ['required', 'integer', 'exists:group_members,id'],
                'type_tuition' => ['nullable', 'string', 'in:Kebersihan,Keamanan,Kematian'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => $validator->errors()->all(),
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $member = GroupMember::where('id', $request->member_id)->first();

            $type = TuitionType::where('tuition_name', $request->type_tuition)->first();

            $groupTuitionSettings = GroupTuitionSetting::where('group_id', $member->group_id)
                ->where('type_tuition_id', $type->id)
                ->get();

            if ($groupTuitionSettings->isEmpty()) {
                return ResponseFormatter::error([
                    'message' => 'No tuition settings found for this group and tuition type.',
                    'error' => 'No tuition settings found for this group and tuition type.',
                ], 'No Tuition Settings', 404);
            }

            $paymentDetails = $groupTuitionSettings->map(function ($setting) use ($member, $type) {
                foreach (range(1, 12) as $month) {
                    $monthPaid = Tuition::where('member_id', $member->id)
                        ->where('type_tuition_id', $type->id)
                        ->whereYear('period', $setting->tuition_period)
                        ->whereMonth('period', $month)
                        ->sum('nominal');

                    $monthlyStatus[$month] = $monthPaid >= $setting->tuition_value;
                }

                return [
                    'year' => $setting->tuition_period,
                    'monthlyStatus' => $monthlyStatus,
                ];
            });

            return ResponseFormatter::success([
                "data" => $paymentDetails
            ], 'Tuition processing completed.');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err->getMessage(),
            ], $err->getMessage(), 500);
        }
    }

    public function getTuitionPaymentMember(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'period'  => ['required', 'integer'],
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'group_id' => ['required', 'integer', 'exists:groups,id'],
                'type_tuition' => ['required', 'string', 'in:Kebersihan,Keamanan,Kematian'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => $validator->errors()->all(),
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $year = $request->period;

            $groupMember = GroupMember::with(['user', 'group', 'member_type'])->where('user_id', $request->user_id)->where('group_id', $request->group_id)->where('status', 'Active')->first();

            $type = TuitionType::where('tuition_name', $request->type_tuition)->first();

            $monthlyStatus = [];
            $tuitionStatus = [];
            $tuitionSettings = GroupTuitionSetting::where('group_id', $groupMember->group_id)
                ->where('type_tuition_id', $type->id)
                ->where('tuition_period', $year)
                ->first();

            // If no tuition settings are found, set all months to false
            if (!$tuitionSettings) {
                foreach (range(1, 12) as $month) {
                    $monthlyStatus[] = (object)[
                        "month" => $month,
                        "status" => false,
                        "tuitionAmount" => 0,
                        "paidAmount" => 0,
                        "tuition" => [],
                    ];
                }

                $tuitionStatus =  [
                    'member' => $groupMember,
                    'monthlyStatus' => $monthlyStatus,
                ];
            } else {
                foreach (range(1, 12) as $month) {
                    $monthPaid = Tuition::where('member_id', $groupMember->id)
                        ->where('type_tuition_id', $type->id)
                        ->whereYear('period', $year)
                        ->whereMonth('period', $month)
                        ->sum('nominal');

                    $tuitionMonth = Tuition::with(['requestTuition', 'member'])->where('member_id', $groupMember->id)
                        ->where('type_tuition_id', $type->id)
                        ->whereYear('period', $year)
                        ->whereMonth('period', $month)
                        ->get();

                    $monthlyStatus[] = (object)[
                        "month" => $month,
                        "status" => $monthPaid >= $tuitionSettings->tuition_value,
                        "tuitionAmount" => $tuitionSettings->tuition_value,
                        "paidAmount" => (int)$monthPaid,
                        "tuition" => $tuitionMonth,
                    ];
                }

                $tuitionStatus = [
                    'member' => $groupMember,
                    'monthlyStatus' => $monthlyStatus,
                ];
            }

            return ResponseFormatter::success([
                'data' => $tuitionStatus,
            ], 'Tuition processing completed.');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => $err->getMessage(),
                'error' => $err->getMessage(),
            ], $err->getMessage(), 500);
        }
    }
}
