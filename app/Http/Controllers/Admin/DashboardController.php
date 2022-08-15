<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $all_data = DB::select("SELECT id, (SELECT COUNT(*) FROM users WHERE role=1) AS TOT_USER, (SELECT COUNT(*) FROM users WHERE role=1 AND user_type='Free') AS FREE_USER, (SELECT COUNT(*) FROM users WHERE role=1 AND user_type='Premium') AS PREMIUM_USER, (SELECT COUNT(*) FROM users WHERE role=0) AS ADMIN_USER FROM users LIMIT 1");
        $all_data = $all_data[0];
        // dd($data);
        return view('dashboard.adminDashboard',compact('all_data'));
    }
}
