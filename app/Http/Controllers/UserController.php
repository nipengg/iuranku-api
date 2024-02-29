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

class UserController extends Controller
{
    public function index()
    {
        $data = User::all();

        return view('admin.user.index', [
            'data' => $data
        ]);
    }

    public function detail($ids)
    {
        $id = Crypt::decryptString($ids);

        $data = User::findOrFail($id);

        return view('admin.user.detail', [
            'data' => $data
        ]);
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
}
