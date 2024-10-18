<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->year;

        if ($date == null) {
            $date = Carbon::now()->startOfYear()->format('d/m/Y');
        }

        $dateCarbon = Carbon::createFromFormat('d/m/Y', $date);
        $year = $dateCarbon->year;

        $userCount = User::whereYear('created_at', $year)->count();
        $groupCount = Group::whereYear('created_at', $year)->count();

        return view('admin.index', [
            'userCount' => $userCount,
            'groupCount' => $groupCount,
            'year' => $date
        ]);
    }
}
