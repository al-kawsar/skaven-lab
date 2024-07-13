<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SiswaStoreRequest;
use App\Http\Requests\SiswaUpdateRequest;
use App\Models\Siswa;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $data['totalData'] = Siswa::count();
        return view('pages.admin.siswa.index', compact('data'));
    }

     public function getData(Request $request){

        $length = $request->input('length', 10); // Ambil panjang data per halaman dari request
        $start = $request->input('start', 0); // Ambil offset data dari request
        $draw = $request->input('draw', 1);

        $data = Siswa::orderBy('created_at', 'desc')
               ->offset($start)
               ->limit($length)
               ->get();

        $counter = $start; // Initialize counter outside the map function

        $transformedData = $data->map(function ($siswa) use (&$counter) {
            return [
                'id' => $siswa->id,
                'name' => $siswa->name,
                'nis' => $siswa->nis,
                'nisn' => $siswa->nisn,
                'alamat' => $siswa->alamat,
                'jenis_kelamin' => $siswa->jenis_kelamin == 'l' ? 'Laki-Laki' : 'Perempuan',
                'agama' => $siswa->agama,
                'tanggal_lahir' => Carbon::parse($siswa->tgl_lahir)->format('d/M/Y'),
                'created_at' => $siswa->created_at->format('d M Y'),
                'number' => ++$counter, // Increment counter for each iteration
            ];
        });

        $totalData = Siswa::count();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData, // Jika ada pencarian, ini mungkin berbeda
            'data' => $transformedData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SiswaStoreRequest $request)
    {
        try {
            $payload = $request->validated();

            $payload['jenis_kelamin'] = $payload['jenis_kelamin'] == 1 ? 'l' : 'p';

            Siswa::create($payload);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Siswa berhasil ditambahkan'
        ]);
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
    public function edit(Siswa $siswa)
    {
        return view('pages.admin.siswa.edit', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SiswaUpdateRequest $request, Siswa $siswa)
     {
         try {
            $payload = $request->validated();

            $payload['jenis_kelamin'] = $payload['jenis_kelamin'] == 1 ? 'l' : 'p';

            $siswa->update($payload);

        } catch (\Exception $e) {
            return to_route('admin.siswa.edit', $siswa->id)->with([
                'status' => 'error',
                'type' => 'toast',
                'message' => $e->getMessage()
            ], 500);
        }

        return to_route('admin.siswa.index')->with([
            'status' => 'success',
            'type' => 'toast',
            'message' => 'Siswa berhasil diubah'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $id)
    {
        try {
            $id->delete();
            return response()->json([
                'message' => 'Data siswa berhasil dihapus'
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
                Siswa::truncate();
                return response()->json(['message' => 'Data siswa berhasil dihapus']);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 500);
            }
    }
}
