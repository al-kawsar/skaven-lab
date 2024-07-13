<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\BorrowingStoreRequest;
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

        return view('pages.lab.index', compact('data'));
    }

    public function show(Lab $lab)
    {
        return view('pages.lab.show', compact('lab'));
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

        return view('pages.lab.form-borrow', compact('labData'));
    }

    public function borrowStore(BorrowingStoreRequest $request, Lab $lab)
    {
        try {
            DB::beginTransaction();
            $payload = $request->validated();

            $lab->update([
                'status' => 'tidak tersedia'
            ]);

            $payload['user_id'] = auth()->id();
            $payload['lab_id'] = $lab->id;

            Borrowing::create($payload);

            DB::commit();

            return response()->json([
                'message' => "Berhasil meminjam lab {$lab->name}",
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function borrowCancel()
    {

    }

    public function borrowApprove()
    {
    }

    public function borrowView()
    {
        $now = Carbon::now();
        Borrowing::query()->where('status', 'menunggu')
            ->where('borrow_date', '<=', $now->toDateString())
            ->where('end_time', '<=', $now->toTimeString())
            ->update(['status' => 'ditolak']);

        return view('pages.lab.my-borrow');
    }

    public function borrowData(Request $request)
    {

        $data = Borrowing::where('user_id', auth()->id())->orderBy('borrow_date', 'desc')->get();

        $transformedData = $data->map(function ($b) use (&$counter) {
            return [
                'id' => $b->id,
                'lab' => $b->lab->name,
                'borrow_date' => $b->borrow_date,
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
}
