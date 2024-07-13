<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab;

class ReportController extends Controller
{
    public function index(Request $request){
         $data = Lab::selectRaw("
            COUNT(*) as totalData,
            SUM(CASE WHEN status = 'tersedia' THEN 1 ELSE 0 END) as totalAvailable
        ")->first()->toArray();

        $data['totalUnavailable'] = 0;
        return view('pages.admin.report.index', compact('data'));
    }
}
