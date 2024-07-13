<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GuruStoreRequest;
use App\Http\Requests\GuruUpdateRequest;
use App\Models\Guru;
use Carbon\Carbon;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $data['totalData'] = Guru::count();
        return view('pages.admin.guru.index', compact('data'));
    }

    public function getData(Request $request){

        $length = $request->input('length', 10); // Ambil panjang data per halaman dari request
        $start = $request->input('start', 0); // Ambil offset data dari request
        $draw = $request->input('draw', 1);

        $data = Guru::orderBy('created_at', 'desc')
               ->offset($start)
               ->limit($length)
               ->get();

        $counter = $start; // Initialize counter outside the map function

        $transformedData = $data->map(function ($guru) use (&$counter) {
            return [
                'id' => $guru->id,
                'name' => $guru->name,
                'nip' => (int) $guru->nip,
                'alamat' => $guru->alamat,
                'jenis_kelamin' => $guru->jenis_kelamin == 'l' ? 'Laki-Laki' : 'Perempuan',
                'agama' => $guru->agama,
                'tanggal_lahir' => Carbon::parse($guru->tgl_lahir)->format('d/M/Y'),
                'created_at' => $guru->created_at->format('d M Y'),
                'number' => ++$counter, // Increment counter for each iteration
            ];
        });

        $totalData = Guru::count();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $transformedData,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
     public function create()
    {
        return view('pages.admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GuruStoreRequest $request)
    {
        try {
            $payload = $request->validated();

            $payload['jenis_kelamin'] = $payload['jenis_kelamin'] == 1 ? 'l' : 'p';

            Guru::create($payload);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Data guru berhasil ditambahkan'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return 'show jancuk';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        return view('pages.admin.guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GuruUpdateRequest $request, Guru $guru)
     {
         try {
            $payload = $request->validated();

            $payload['jenis_kelamin'] = $payload['jenis_kelamin'] == 1 ? 'l' : 'p';

            $guru->update($payload);

        } catch (\Exception $e) {
            return to_route('admin.guru.edit', $guru->id)->with([
                'status' => 'error',
                'type' => 'toast',
                'message' => $e->getMessage()
            ], 500);
        }

        return to_route('admin.guru.index')->with([
            'status' => 'success',
            'type' => 'toast',
            'message' => 'guru berhasil diubah'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $id)
    {
        try {
            $id->delete();
            return response()->json([
                'message' => 'Data guru berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyAll()
    {
            try {
                Guru::truncate();
                return response()->json(['message' => 'Data guru berhasil dihapus']);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 500);
            }
    }
}
