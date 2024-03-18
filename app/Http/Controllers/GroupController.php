<?php

namespace App\Http\Controllers;

use App\Enum\Constant;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class GroupController extends Controller
{
    public function index()
    {
        $data = Group::all();

        return view('admin.group.index', [
            'data' => $data,
        ]);
    }

    public function create()
    {
        $users = User::where('role', 'User')->whereNull('deleted_at')->get();

        return view('admin.group.create', [
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|max:50',
            'group_description' => 'max:250',
            'group_address' => 'required|max:250',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            $errorListItems = '';

            foreach ($errors as $error) {
                $errorListItems .= "<li>$error</li>";
            }

            $errorMessage = "<ul>$errorListItems</ul>";

            Alert::html('Invalid Input', $errorMessage, 'error');

            return redirect()->back()->withInput();
        }

        try {
            DB::transaction(function () use ($data): void {
                $group = Group::create([
                    'group_name' => $data['group_name'],
                    'group_description' => $data['group_description'],
                    'group_address' => $data['group_address'],
                    'user_in' => Auth::user()->id,
                ]);
    
                foreach ($data['users'] as $item) {
                    GroupMember::create([
                        'user_id' => $item,
                        'group_id' => $group->id,
                        'member_type_id' => Constant::MEMBER_TYPE['Group Leader'],
                        'status' => Constant::STATUS['Active'],
                        'join_date' => Carbon::now(),
                        'leave_date' => null,
                        'leave_type' => null,
                        'leave_note' => null,
                    ]);
                }
            });
        } catch (\Throwable $th) {
            Alert::html('Invalid Input', 'Something went wrong...', 'error');
            return redirect()->back()->withInput();
        }

        Alert::success('Success!', 'Group Stored');
        return redirect()->route('admin.group.index');
    }

    public function detail($ids)
    {
        $id = Crypt::decryptString($ids);

        $data = Group::findOrFail($id);

        return view('admin.group.detail', [
            'data' => $data
        ]);
    }

    public function update(Request $request, $ids)
    {
        $data = $request->all();
        $id  = Crypt::decryptString($ids);

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|max:250|string',
            'group_description' => 'max:500|string',
            'group_address' => 'required|max:250|string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            $errorListItems = '';

            foreach ($errors as $error) {
                $errorListItems .= "<li>$error</li>";
            }

            $errorMessage = "<ul>$errorListItems</ul>";

            Alert::html('Invalid Input', $errorMessage, 'error');

            return redirect()->back()->withInput();
        }

        try {
            Group::where('id', $id)->update([
                'group_name' => $data['group_name'],
                'group_description' => $data['group_description'],
                'group_address' => $data['group_address'],
                'user_in' => Auth::user()->id,
            ]);
        } catch (\Throwable $th) {
            Alert::html('Invalid Input', 'Something went wrong...', 'error');

            return redirect()->back()->withInput();
        }

        Alert::success('Success!', 'Group Updated');
        return redirect()->route('admin.group.detail', Crypt::encryptString($id));
    }
}
