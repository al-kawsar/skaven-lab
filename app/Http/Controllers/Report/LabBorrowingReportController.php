<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\LabBorrowing;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\DB;

class LabBorrowingReportController extends Controller
{
    /**
     * Display report page with filter options
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Hanya admin yang bisa akses
        if (!auth()->user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }
        
        $labs = Lab::orderBy('name')->get();
        return view('pages.reports.lab-borrowing.index', compact('labs'));
    }
    
    /**
     * Generate usage report
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function usageReport(Request $request)
    {
        // Validasi input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'lab_id' => 'nullable|exists:labs,id',
            'status' => 'nullable|in:menunggu,disetujui,ditolak,selesai,dibatalkan',
            'format' => 'nullable|in:html,pdf'
        ]);
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $labId = $request->lab_id;
        $status = $request->status;
        $format = $request->format ?? 'html';
        
        // Query data peminjaman
        $query = LabBorrowing::with(['lab', 'user'])
            ->whereBetween('borrow_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('borrow_date', 'asc')
            ->orderBy('start_time', 'asc');
            
        if ($labId) {
            $query->where('lab_id', $labId);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $borrowings = $query->get();
        $lab = $labId ? Lab::find($labId) : null;
        
        // Hitung statistik
        $statistics = [
            'total' => $borrowings->count(),
            'approved' => $borrowings->where('status', 'disetujui')->count(),
            'rejected' => $borrowings->where('status', 'ditolak')->count(),
            'pending' => $borrowings->where('status', 'menunggu')->count(),
            'completed' => $borrowings->where('status', 'selesai')->count(),
            'canceled' => $borrowings->where('status', 'dibatalkan')->count(),
        ];
        
        // Transformasi data untuk tampilan
        $borrowings = $borrowings->map(function($item) {
            $startDateTime = Carbon::parse($item->borrow_date . ' ' . $item->start_time);
            $endDateTime = Carbon::parse($item->borrow_date . ' ' . $item->end_time);
            $durationMinutes = $startDateTime->diffInMinutes($endDateTime);
            
            return [
                'id' => $item->id,
                'lab_name' => $item->lab->name,
                'borrower' => $item->user->name,
                'borrow_date' => DateHelper::formatLong($item->borrow_date),
                'time_range' => $item->start_time . ' - ' . $item->end_time,
                'duration' => floor($durationMinutes / 60) . ' jam ' . ($durationMinutes % 60) . ' menit',
                'event' => $item->event,
                'status' => $item->status,
                'created_at' => $item->created_at->locale('id')->isoFormat('D MMMM Y'),
                'created_time' => $item->created_at->format('H:i'),
                'notes' => $item->notes
            ];
        });
        
        // Render laporan sesuai format
        if ($format === 'pdf') {
            // Uncomment jika menggunakan package seperti Dompdf
            // $pdf = PDF::loadView('pages.reports.lab-borrowing.usage-pdf', [
            //     'borrowings' => $borrowings,
            //     'start_date' => $startDate,
            //     'end_date' => $endDate,
            //     'lab' => $lab,
            //     'statistics' => $statistics
            // ]);
            // return $pdf->download('laporan-peminjaman-lab.pdf');
            
            // Untuk sementara, gunakan view biasa
            return view('pages.reports.lab-borrowing.usage-print', [
                'borrowings' => $borrowings,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'lab' => $lab,
                'statistics' => $statistics
            ]);
        }
        
        return view('pages.reports.lab-borrowing.usage', [
            'borrowings' => $borrowings,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'lab' => $lab,
            'statistics' => $statistics
        ]);
    }
    
    /**
     * Generate schedule report
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function scheduleReport(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'nullable|in:html,pdf'
        ]);
        
        $lab = Lab::findOrFail($request->lab_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $format = $request->format ?? 'html';
        
        // Batasi range maksimal 2 minggu
        if ($startDate->diffInDays($endDate) > 14) {
            $endDate = $startDate->copy()->addDays(14);
        }
        
        // Generate array of dates
        $dates = collect(CarbonPeriod::create($startDate, $endDate));
        
        // Get approved bookings in date range
        $bookings = LabBorrowing::with('user')
            ->where('lab_id', $lab->id)
            ->where('status', 'disetujui')
            ->whereBetween('borrow_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();
        
        // Organize schedule by date and time slot
        $schedule = [];
        foreach ($dates as $date) {
            $dateString = $date->format('Y-m-d');
            $schedule[$dateString] = [];
        }
        
        foreach ($bookings as $booking) {
            $date = $booking->borrow_date;
            
            if (!isset($schedule[$date])) {
                $schedule[$date] = [];
            }
            
            $schedule[$date][] = $booking;
        }
        
        // Render laporan sesuai format
        if ($format === 'pdf') {
            // Uncomment jika menggunakan package seperti Dompdf
            // $pdf = PDF::loadView('pages.reports.lab-borrowing.schedule-pdf', [
            //     'lab' => $lab,
            //     'start_date' => $startDate,
            //     'end_date' => $endDate,
            //     'dates' => $dates,
            //     'schedule' => $schedule
            // ]);
            // return $pdf->download('jadwal-lab.pdf');
            
            return view('pages.reports.lab-borrowing.schedule-print', [
                'lab' => $lab,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'dates' => $dates,
                'schedule' => $schedule
            ]);
        }
        
        return view('pages.reports.lab-borrowing.schedule', [
            'lab' => $lab,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'dates' => $dates,
            'schedule' => $schedule
        ]);
    }
    
    /**
     * Generate summary report
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function summaryReport(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'format' => 'nullable|in:html,pdf'
        ]);
        
        $year = $request->year;
        $format = $request->format ?? 'html';
        
        // Dapatkan data bulanan
        $monthlyData = DB::table('lab_borrowings')
            ->selectRaw('MONTH(borrow_date) as month, status, COUNT(*) as count')
            ->whereYear('borrow_date', $year)
            ->groupBy('month', 'status')
            ->orderBy('month')
            ->get();
        
        // Dapatkan data per-lab
        $labData = DB::table('lab_borrowings')
            ->join('labs', 'lab_borrowings.lab_id', '=', 'labs.id')
            ->selectRaw('labs.name as lab_name, status, COUNT(*) as count')
            ->whereYear('borrow_date', $year)
            ->groupBy('labs.name', 'status')
            ->orderBy('count', 'desc')
            ->get();
        
        // Format data untuk chart
        $months = [];
        $approvedData = array_fill(0, 12, 0);
        $pendingData = array_fill(0, 12, 0);
        $rejectedData = array_fill(0, 12, 0);
        $completedData = array_fill(0, 12, 0);
        
        foreach ($monthlyData as $item) {
            $monthIndex = $item->month - 1; // 0-based index for JS array
            
            switch ($item->status) {
                case 'disetujui':
                    $approvedData[$monthIndex] = $item->count;
                    break;
                case 'menunggu':
                    $pendingData[$monthIndex] = $item->count;
                    break;
                case 'ditolak':
                    $rejectedData[$monthIndex] = $item->count;
                    break;
                case 'selesai':
                    $completedData[$monthIndex] = $item->count;
                    break;
            }
        }
        
        // Format lab data
        $labNames = [];
        $labStats = [];
        
        foreach ($labData as $item) {
            if (!in_array($item->lab_name, $labNames)) {
                $labNames[] = $item->lab_name;
                $labStats[$item->lab_name] = [
                    'approved' => 0,
                    'pending' => 0,
                    'rejected' => 0,
                    'completed' => 0,
                    'total' => 0
                ];
            }
            
            switch ($item->status) {
                case 'disetujui':
                    $labStats[$item->lab_name]['approved'] = $item->count;
                    break;
                case 'menunggu':
                    $labStats[$item->lab_name]['pending'] = $item->count;
                    break;
                case 'ditolak':
                    $labStats[$item->lab_name]['rejected'] = $item->count;
                    break;
                case 'selesai':
                    $labStats[$item->lab_name]['completed'] = $item->count;
                    break;
            }
            
            $labStats[$item->lab_name]['total'] += $item->count;
        }
        
        // Sort labs by total usage
        uasort($labStats, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        
        // Prepare chart data
        $chartData = [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'approved' => $approvedData,
            'pending' => $pendingData,
            'rejected' => $rejectedData,
            'completed' => $completedData
        ];
        
        // Render laporan sesuai format
        if ($format === 'pdf') {
            // Uncomment jika menggunakan package seperti Dompdf
            // $pdf = PDF::loadView('pages.reports.lab-borrowing.summary-pdf', [
            //     'year' => $year,
            //     'chartData' => $chartData,
            //     'labStats' => $labStats
            // ]);
            // return $pdf->download('ringkasan-peminjaman-lab.pdf');
            
            return view('pages.reports.lab-borrowing.summary-print', [
                'year' => $year,
                'chartData' => $chartData,
                'labStats' => $labStats
            ]);
        }
        
        return view('pages.reports.lab-borrowing.summary', [
            'year' => $year,
            'chartData' => $chartData,
            'labStats' => $labStats
        ]);
    }
    
    /**
     * Print bukti peminjaman
     *
     * @param LabBorrowing $borrowing
     * @return \Illuminate\View\View
     */
    public function printBorrowing(LabBorrowing $borrowing)
    {
        // Cek akses
        if ($borrowing->user_id !== auth()->id() && !auth()->user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Anda tidak memiliki akses ke data ini');
        }
        
        // Cek status untuk non-admin
        if (!auth()->user()->hasRole(['admin', 'superadmin']) && $borrowing->status !== 'disetujui') {
            abort(403, 'Hanya peminjaman yang sudah disetujui yang dapat dicetak');
        }
        
        return view('pages.reports.lab-borrowing.print-borrowing', [
            'borrowing' => $borrowing,
            'school' => [
                'name' => 'SMK NEGERI X KOTA Y',
                'address' => 'Jl. Pendidikan No. 123, Kota Y',
                'phone' => '(021) 1234567',
                'email' => 'info@smkn-x.sch.id',
                'website' => 'www.smkn-x.sch.id',
                'logo' => asset('assets/images/logo.png')
            ]
        ]);
    }
}
