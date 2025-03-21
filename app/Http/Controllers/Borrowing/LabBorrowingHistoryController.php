<?php

namespace App\Http\Controllers\Borrowing;

use App\Http\Controllers\Controller;
use App\Models\LabBorrowingHistory;
use Illuminate\Http\Request;

class LabBorrowingHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Hanya admin yang bisa melihat
        if (!auth()->user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        return view('pages.borrowing.lab.management.history');
    }

    public function getData(Request $request)
    {
        // Hanya admin yang bisa melihat
        if (!auth()->user()->hasRole(['admin', 'superadmin'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = LabBorrowingHistory::with(['borrowing.lab', 'borrowing.user', 'user']);

        // Filter berdasarkan status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter berdasarkan lab
        if ($request->has('lab_id') && !empty($request->lab_id)) {
            $query->whereHas('borrowing', function($q) use ($request) {
                $q->where('lab_id', $request->lab_id);
            });
        }

        // Filter berdasarkan user
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->whereHas('borrowing', function($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        $data = $query->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => $data,
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
            ]
        ]);
    }
}