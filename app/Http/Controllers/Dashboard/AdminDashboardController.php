<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LabBorrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return $this->adminDashboard();
    }

    private function adminDashboard()
    {
        try {
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
                'totalItemLoan' => 0,
                'totalUser' => User::count(),
                'totalRoomBookings' => LabBorrowing::count(),
                'totalEquipmentLoan' => 4,
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
                    'roomBookings' => [3, 5, 4, 6, 4]
                ],
            ];

            return view('pages.dashboard.admin', compact('data'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Dashboard error: ' . $e->getMessage());

            // Return a basic view with error message
            return view('pages.dashboard.admin', [
                'data' => [
                    'totalItemLoan' => 0,
                    'totalUser' => 0,
                    'totalRoomBookings' => 0,
                    'totalEquipmentLoan' => 0,
                    'totalStudent' => 0,
                    'totalTeacher' => 0,
                ],
                'error' => 'Terjadi kesalahan saat memuat data dashboard.'
            ]);
        }
    }
}
