<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $data = User::all();

        return view('admin.user.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'phone' => 'required|max:12|unique:users',
            'address' => 'required|max:250',
            'gender' => 'required',
            'email' => 'string|max:255|unique:users',
            'address' => 'string|max:255',
            'password' => 'string|confirmed|max:255',
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

        User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        Alert::success('Success!', 'User Stored');
        return redirect()->route('admin.user.index');
    }

    public function detail($ids)
    {
        $id = Crypt::decryptString($ids);

        $data = User::findOrFail($id);

        return view('admin.user.detail', [
            'data' => $data
        ]);
    }

    public function update(Request $request, $ids)
    {
        $data = $request->all();
        $id = Crypt::decryptString($ids);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'phone' => 'required|max:12|unique:users,phone,' . $id,
            'address' => 'required|max:250',
            'gender' => 'required',
            'email' => 'string|max:255|unique:users,email,' . $id,
            'address' => 'string|max:255',
            'role' => 'required',
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

        User::where('id', $id)->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'address' => $data['address'],
            'role' => $data['role'],
        ]);

        Alert::success('Success!', 'User Updated');
        return redirect()->route('admin.user.detail', Crypt::encryptString($id));
    }

    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        if ($validator->fails()) {
            Alert::error('Upload Failed', $validator->messages()->all()[0]);
            return redirect()->back();
        }

        $file = $request->file('file');

        $fileName = date('YmdHis') . '_' . 'Import.' . $file->getClientOriginalExtension();

        $file->move('import/user', $fileName);


        try {
            Excel::import(new UsersImport, public_path('/import/user/' . $fileName));
        } catch (\Exception $e) {
            Alert::error('Upload Failed', $e->getMessage());
            return redirect()->back();
        }
        Alert::success('User Import Success!', 'Data Inserted Successfully');
        return redirect()->route('admin.user.index');
    }

    public function delete($ids)
    {
        $id = Crypt::decryptString($ids);

        $data = User::findOrFail($id);

        $data->delete();

        Alert::success('Delete User Success!', 'User Deleted');
        return redirect()->route('admin.user.index');
    }
}
