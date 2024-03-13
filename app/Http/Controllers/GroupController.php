<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
        return view('admin.group.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|max:50',
            'group_description' => 'max:250',
            'group_address' => 'required|max:250',
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors()->all();

            $errorListItems = '';

            foreach ($errors as $error) {
                $errorListItems .= "<li>$error</li>";
            }

            $errorMessage = "<ul>$errorListItems</ul>";

            Alert::html('Invalid Input', $errorMessage, 'error');

            return redirect()->back()->withInput();
        }

        Group::create([
            'group_name' => $data['group_name'],
            'group_description' => $data['group_description'],
            'group_address' => $data['group_address'],
            'user_in' => Auth::user()->id,
        ]);

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
}
