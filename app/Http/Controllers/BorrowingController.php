<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = Carbon::now();
        Borrowing::query()->where('status', 'menunggu')
            ->where('borrow_date', '<=', $now->toDateString())
            ->where('end_time', '<=', $now->toTimeString())
            ->update(['status' => 'ditolak']);

        return view('pages.admin.borrow.index');
    }

    public function getData(Request $request)
    {

        $data = Borrowing::orderBy('borrow_date', 'desc')->get();

        $transformedData = $data->map(function ($b) use (&$counter) {
            return [
                'id' => $b->id,
                'peminjam' => $b->user->name,
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
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
