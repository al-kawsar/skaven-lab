<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LabBorrowing;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return $this->userDashboard();
    }

    private function userDashboard()
    {
        $user_id = (int) auth()->id();
        $data = [
            'totalBorrow' => LabBorrowing::where('user_id', $user_id)->count(),
            'totalApproved' => LabBorrowing::where('user_id', $user_id)->where('status', 'disetujui')->count(),
            'totalRejected' => LabBorrowing::where('user_id', $user_id)->where('status', 'ditolak')->count(),
            'totalPending' => LabBorrowing::where('user_id', $user_id)->where('status', 'menunggu')->count(),
            'activeLoans' => collect([
                (object) ['item' => 'Laptop A', 'due_date' => '2023-12-15', 'status' => 'active'],
                (object) ['item' => 'Proyektor B', 'due_date' => '2023-12-20', 'status' => 'active']
            ]),
            'popularCategories' => collect([
                (object) ['name' => 'Elektronik', 'items_count' => 25],
                (object) ['name' => 'Alat Lab', 'items_count' => 20],
                (object) ['name' => 'Perangkat Komputer', 'items_count' => 15],
                (object) ['name' => 'Alat Praktikum', 'items_count' => 10],
                (object) ['name' => 'Peralatan Audio', 'items_count' => 8]
            ]),
            'timeSlots' => ['07:00-08:30', '08:30-10:00', '10:15-11:45', '12:45-14:15', '14:15-15:45'],
            'schedule' => collect([
                (object) ['room' => 'Ruang Multimedia', 'time' => '08:30-10:00', 'class' => 'XII RPL 1'],
                (object) ['room' => 'Ruang Praktikum', 'time' => '10:15-11:45', 'class' => 'XI IPA 2']
            ]),
            'announcements' => collect([
                (object) ['title' => 'Pemeliharaan Ruangan', 'content' => 'Ruang Multimedia akan ditutup untuk pemeliharaan', 'created_at' => now()],
                (object) ['title' => 'Jadwal Baru', 'content' => 'Perubahan jadwal penggunaan ruangan', 'created_at' => now()->subDay()],
                (object) ['title' => 'Pengumuman Penting', 'content' => 'Harap mengembalikan peminjaman tepat waktu', 'created_at' => now()->subDays(2)]
            ]),
        ];

        return view('pages.dashboard.user', compact('data'));
    }
}
