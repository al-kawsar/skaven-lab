<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Lab;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = Carbon::now();
        // Borrowing::query()->where('status', 'menunggu')->where('lab_id', '9cf864c8-f46c-4850-aa38-34a655b72d4b')
        //     ->where('borrow_date', '<=', $now->toDateString())
        //     ->where('end_time', '<=', $now->toTimeString())
        //     ->update(['status' => 'ditolak']);

        return view(view: 'pages.labs.borrow');
    }

    public function getData(Request $request)
    {
        try {

            $lab = Lab::with([
                'borrowings' => function ($query) {
                    $query->orderBy('borrow_date', 'desc');
                }
            ])
                ->first();


            // Jika lab tidak ditemukan
            if (!$lab) {
                return response()->json([
                    'data' => [] // Mengirim array kosong
                ]);
            }

            // Mengambil data peminjaman dari lab
            $data = $lab->borrowings;

            // Jika peminjaman kosong
            if ($data->isEmpty()) {
                return response()->json([
                    'data' => []
                ]);
            }

            $transformedData = $data->map(function ($b) use (&$counter): array {
                return [
                    'id' => $b->id,
                    'peminjam' => $b->user->name,
                    'borrow_date' => Carbon::parse(time: $b->borrow_date)->format(format: 'F j, Y'),
                    'start_time' => $b->start_time,
                    'end_time' => $b->end_time,
                    'event' => $b->event,
                    'status' => $b->status,
                    'number' => ++$counter,
                ];
            });
            return response()->json(data: [
                'data' => $transformedData,
            ]);
        } catch (\Exception $e) {
            return response()->json(data: [
                'status' => false,
                'message' => $e->getMessage()
            ], status: 500);
        }
    }


}
