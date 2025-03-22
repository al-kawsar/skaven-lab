<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Lab;
use App\Models\User;
use Carbon\Carbon;
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
        // Data dummy untuk barang dengan stok menipis
        $lowStockItems = collect([
            (object) ['name' => 'Laptop', 'stock' => 3],
            (object) ['name' => 'Proyektor', 'stock' => 2],
            (object) ['name' => 'Mouse', 'stock' => 4]
        ]);

        // Data dummy untuk peminjaman yang terlambat
        $overdueLoans = collect([
            (object) [
                'borrower_name' => 'John Doe',
                'item' => (object) ['name' => 'Laptop A'],
                'return_date' => Carbon::parse('2023-12-01'),
                'days_overdue' => Carbon::parse('2023-12-01')->diffInDays(now())
            ],
            (object) [
                'borrower_name' => 'Jane Smith',
                'item' => (object) ['name' => 'Proyektor B'],
                'return_date' => Carbon::parse('2023-12-02'),
                'days_overdue' => Carbon::parse('2023-12-02')->diffInDays(now())
            ]
        ]);

        $data = [
            'totalLabLoan' => Lab::count(),
            'totalUser' => User::count(),
            'totalBorrowingLab' => 3,
            'totalItemLoan' => 4,
            'totalStudent' => 3000,
            'totalTeacher' => 100,
            'lowStockItems' => $lowStockItems,
            'overdueLoans' => $overdueLoans,
            'popularItems' => collect([
                ['name' => 'Laptop', 'total_borrowed' => 50],
                ['name' => 'Proyektor', 'total_borrowed' => 45],
                ['name' => 'Mouse', 'total_borrowed' => 30],
                ['name' => 'Keyboard', 'total_borrowed' => 25],
                ['name' => 'Monitor', 'total_borrowed' => 20]
            ]),
            'recentActivities' => collect([
                [
                    'title' => 'Peminjaman Baru',
                    'description' => 'John meminjam Laptop',
                    'type_class' => 'bg-primary',
                    'icon' => 'fa-laptop',
                    'created_at' => now()
                ],
                [
                    'title' => 'Pengembalian',
                    'description' => 'Jane mengembalikan Proyektor',
                    'type_class' => 'bg-success',
                    'icon' => 'fa-undo',
                    'created_at' => now()->subHours(2)
                ],
                [
                    'title' => 'Stok Update',
                    'description' => 'Stok Mouse ditambahkan',
                    'type_class' => 'bg-info',
                    'icon' => 'fa-plus',
                    'created_at' => now()->subHours(5)
                ]
            ]),
            'chartData' => [
                'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                'itemLoans' => [5, 8, 12, 7, 10],
                'labLoans' => [3, 5, 4, 6, 4]
            ],
        ];

        return view('pages.dashboard.admin', compact('data'));
    }

    private function userDashboard()
    {
        $user_id = (int) auth()->id();
        $data = [
            'totalLab' => Lab::where('status', 'tidak tersedia')->count(),
            'totalBorrow' => Borrowing::where('user_id', $user_id)->count(),
            'totalApproved' => Borrowing::where('user_id', $user_id)->where('status', 'disetujui')->count(),
            'totalRejected' => Borrowing::where('user_id', $user_id)->where('status', 'ditolak')->count(), // Adjust these counts based on actual logic
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
            'labs' => collect([
                (object) ['name' => 'Lab Komputer', 'status' => 'tersedia'],
                (object) ['name' => 'Lab Fisika', 'status' => 'tidak tersedia'],
                (object) ['name' => 'Lab Kimia', 'status' => 'tersedia']
            ]),
            'timeSlots' => ['07:00-08:30', '08:30-10:00', '10:15-11:45', '12:45-14:15', '14:15-15:45'],
            'schedule' => collect([
                (object) ['lab' => 'Lab Komputer', 'time' => '08:30-10:00', 'class' => 'XII RPL 1'],
                (object) ['lab' => 'Lab Fisika', 'time' => '10:15-11:45', 'class' => 'XI IPA 2']
            ]),
            'announcements' => collect([
                (object) ['title' => 'Pemeliharaan Lab', 'content' => 'Lab Komputer akan ditutup untuk pemeliharaan', 'created_at' => now()],
                (object) ['title' => 'Jadwal Baru', 'content' => 'Perubahan jadwal penggunaan lab', 'created_at' => now()->subDay()],
                (object) ['title' => 'Pengumuman Penting', 'content' => 'Harap mengembalikan peminjaman tepat waktu', 'created_at' => now()->subDays(2)]
            ]),
        ];

        return view('pages.dashboard.user', compact('data'));
    }
}
