<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Guru;
use App\Models\Lab;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __invoke(Request $request)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->userDashboard();
    }

    private function adminDashboard()
    {
        $data = [
            'totalLab' => Lab::count(),
            'totalUser' => User::count() + Guru::count(),
            'totalBorrowing' => Borrowing::count(),
        ];

        return view('pages.admin.dashboard', compact('data'));
    }

    private function userDashboard()
    {
        $data = [
            'totalLab' => Lab::where('status', 'tidak tersedia')->count(),
            'totalBorrow' => Borrowing::where('user_id', auth()->id())->count(),
            'totalApproved' => Borrowing::where('user_id', auth()->id())->where('status', 'disetujui')->count(), // Adjust these counts based on actual logic
            'totalRejected' => Borrowing::where('user_id', auth()->id())->where('status', 'ditolak')->count() // Adjust these counts based on actual logic
        ];

        return view('pages.dashboard', compact('data'));
    }
}
