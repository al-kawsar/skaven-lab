<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\Borrowing\StoreRequest;
use App\Models\Borrowing;
use App\Models\Lab;
use App\Helpers\NumberFormatter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabUserController extends Controller
{
    public function index(Request $request)
    {
        $firstLab = Lab::with('file')->orderBy('id', 'asc')->first();

        if (!$firstLab) {
            return back()->with([
                'type' => 'alert',
                'message' => 'Lab tidak ditemukan.',
            ]);
        }

        // return to_route('lab.borrow', $firstLab->id);

        $now = Carbon::now();

        $borrowedLabIds = Borrowing::query()
            ->where('borrow_date', '<=', $now->toDateString())
            ->where('end_time', '<=', $now->toTimeString())
            ->pluck('lab_id');

        // Mengubah status lab menjadi tersedia (misalnya status 1 adalah tersedia)
        Lab::whereIn('id', $borrowedLabIds)->update(['status' => 1]);

        $data = Lab::with('file')->orderBy('status', 'asc')->limit(6)->get();


        $data = $data->map(function ($lab) {
            return [
                'id' => $lab->id,
                'name' => $lab->name,
                'capacity' => NumberFormatter::format($lab->capacity),
                'status' => $lab->status,
                'thumbnail' => $lab->file->path_name ?? '',
                'location' => strlen($lab->location) > 10 ? substr($lab->location, 0, 10) . '...' : $lab->location,
            ];
        });

        return view('pages.labs.user-index', compact('data'));
    }

    public function show(Lab $lab)
    {
        return view('pages.labs.show', compact('lab'));
    }

    public function borrow(Lab $lab)
    {

        $labData = [
            'id' => $lab->id,
            'name' => $lab->name,
            'capacity' => NumberFormatter::format($lab->capacity),
            'status' => $lab->status,
            'facilities' => $lab->facilities,
            'thumbnail' => $lab->file->path_name ?? '',
            'location' => strlen($lab->location) > 15 ? substr($lab->location, 0, 15) . '...' : $lab->location,
        ];

        return view('pages.labs.form-borrow', compact('labData'));
    }

    public function borrowStore(StoreRequest $request, Lab $lab)
    {
        try {
            $payload = $request->validated();
            $borrowDate = Carbon::parse($payload['borrow_date'])->format('Y-m-d');

            // Check for time conflicts using more efficient query
            if ($this->hasTimeConflict($lab->id, $borrowDate, $payload['start_time'], $payload['end_time'])) {
                return response()->json([
                    'message' => 'Waktu yang dipilih sudah dipesan. Silahkan pilih waktu lain.',
                ], 422);
            }

            // Use transaction to ensure data integrity
            return DB::transaction(function () use ($payload, $lab, $borrowDate) {
                // Create new borrowing record
                Borrowing::create([
                    'user_id' => auth()->id(),
                    'lab_id' => $lab->id,
                    'borrow_date' => $borrowDate,
                    'start_time' => $payload['start_time'],
                    'end_time' => $payload['end_time'],
                    'event' => $payload['event'],
                    'notes' => $payload['notes'] ?? null,
                    'status' => 'menunggu'
                ]);

                return response()->json([
                    'message' => "Berhasil mengajukan peminjaman {$lab->name}",
                ], 200);
            });

        } catch (\Exception $e) {
            report($e); // Log the exception
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses permintaan.'
            ], 500);
        }
    }

    /**
     * Check if there's a time conflict with existing approved bookings
     * 
     * @param string $labId
     * @param string $date
     * @param string $startTime
     * @param string $endTime
     * @return bool
     */
    private function hasTimeConflict($labId, $date, $startTime, $endTime)
    {
        return Borrowing::where('lab_id', $labId)
            ->where('borrow_date', $date)
            ->where('status', 'disetujui')
            ->where(function ($query) use ($startTime, $endTime) {
                // Case 1: Existing booking starts during requested time
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '>=', $startTime)
                        ->where('start_time', '<', $endTime);
                })
                    // Case 2: Existing booking ends during requested time
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                    $q->where('end_time', '>', $startTime)
                        ->where('end_time', '<=', $endTime);
                })
                    // Case 3: Existing booking completely contains requested time
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<=', $startTime)
                        ->where('end_time', '>=', $endTime);
                });
            })
            ->exists();
    }

    public function borrowView()
    {
        $now = Carbon::now();
        Borrowing::query()->where('status', 'menunggu')
            ->where('borrow_date', '<=', $now->toDateString())
            ->where('end_time', '<=', $now->toTimeString())
            ->update(['status' => 'ditolak']);

        return view('pages.labs.my-borrow');
    }

    public function borrowData(Request $request)
    {

        $data = Borrowing::where('user_id', auth()->id())->orderBy('borrow_date', 'desc')->get();

        $transformedData = $data->map(function ($b) use (&$counter) {
            return [
                'id' => $b->id,
                'lab' => $b->lab->name,
                'borrow_date' => Carbon::parse($b->borrow_date)->format('F j, Y'),
                'start_time' => $b->start_time,
                'end_time' => $b->end_time,
                'event' => $b->event,
                'status' => $b->status,
                'number' => ++$counter, // Increment counter for each iteration
            ];
        });

        return response()->json([
            'data' => $transformedData,
        ]);
    }


    public function borrowDetail(Borrowing $borrow)
    {
        // Cek kepemilikan borrowing
        // if ($borrow->user_id !== auth()->id()) {
        //     return response()->json([
        //         'message' => 'Anda tidak memiliki akses ke data ini',
        //     ], 403);
        // }

        $detailData = [
            'id' => $borrow->id,
            'lab_name' => $borrow->lab->name,
            'borrow_date' => Carbon::parse($borrow->borrow_date)->format('d F Y'),
            'start_time' => $borrow->start_time,
            'end_time' => $borrow->end_time,
            'event' => $borrow->event,
            'status' => $borrow->status,
            'notes' => $borrow->notes,
            'created_at' => $borrow->created_at->format('d F Y H:i'),
            'borrower' => $borrow->user->name ?? 'undefined',
        ];

        return response()->json([
            'data' => $detailData,
        ]);
    }

    public function borrowCancel(Borrowing $borrow)
    {
        // Cek kepemilikan borrowing
        if ($borrow->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk membatalkan peminjaman ini',
            ], 403);
        }

        // Cek apakah status masih "menunggu"
        if ($borrow->status !== 'menunggu') {
            return response()->json([
                'message' => 'Hanya peminjaman dengan status menunggu yang dapat dibatalkan',
            ], 400);
        }

        try {
            // Update status menjadi "dibatalkan"
            $borrow->status = 'dibatalkan';
            $borrow->notes = 'Dibatalkan oleh peminjam pada ' . now()->format('d/m/Y H:i');
            $borrow->save();

            // Jika peminjaman sedang menunggu, lab akan tetap tersedia
            // Tidak perlu mengubah status lab

            return response()->json([
                'message' => 'Peminjaman berhasil dibatalkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function borrowPrint(Borrowing $borrow)
    {
        // Cek kepemilikan borrowing
        if ($borrow->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini');
        }

        // Cek apakah status sudah "disetujui"
        if ($borrow->status !== 'disetujui') {
            abort(400, 'Hanya peminjaman dengan status disetujui yang dapat dicetak');
        }

        $data = [
            'borrow' => $borrow,
            'lab' => $borrow->lab,
            'user' => $borrow->user,
            'date' => Carbon::now()->format('d F Y'),
        ];

        return view('pages.labs.print-borrow', $data);
    }


    public function borrowFilter(Request $request)
    {
        $query = Borrowing::where('user_id', auth()->id());

        // Filter berdasarkan status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('borrow_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('borrow_date', '<=', $request->end_date);
        }

        $data = $query->orderBy('borrow_date', 'desc')->get();

        $transformedData = $data->map(function ($b) {
            return [
                'id' => $b->id,
                'lab' => $b->lab->name,
                'borrow_date' => Carbon::parse($b->borrow_date)->format('F j, Y'),
                'start_time' => $b->start_time,
                'end_time' => $b->end_time,
                'event' => $b->event,
                'status' => $b->status,
                'number' => 0, // Will be fixed on frontend
            ];
        });

        return response()->json([
            'data' => $transformedData,
        ]);
    }
}
