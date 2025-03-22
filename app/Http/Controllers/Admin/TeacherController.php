<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Teacher\StoreRequest;
use App\Http\Requests\Teacher\UpdateRequest;
use App\Models\Teacher;
use Carbon\Carbon;


class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $data['totalData'] = Teacher::count();
        return view('pages.teachers.index', compact('data'));
    }

    public function getData(Request $request)
    {
        $length = $request->input('length', 10); // Ambil panjang data per halaman dari request
        $start = $request->input('start', 0); // Ambil offset data dari request
        $draw = $request->input('draw', 1);
        $search = $request->input('search', ''); // Ambil input pencarian

        // Query dasar
        $query = Teacher::orderBy('created_at', 'desc');

        // Jika ada pencarian, tambahkan filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('nip', 'LIKE', "%$search%");
            });
        }

        // Hitung total data setelah filter
        $totalFiltered = $query->count();

        // Ambil data dengan paginasi
        $data = $query->offset($start)->limit($length)->get();

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

        $totalData = Teacher::count(); // Total data tanpa filter

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered, // Gunakan total data setelah filter
            'data' => $transformedData,
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $payload = $request->validated();

            $payload['jenis_kelamin'] = $payload['jenis_kelamin'] == 1 ? 'l' : 'p';

            Teacher::create($payload);
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
    public function edit(Teacher $teacher)
    {
        return view('pages.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Teacher $guru)
    {
        try {
            $payload = $request->validated();

            $payload['jenis_kelamin'] = $payload['jenis_kelamin'] == 1 ? 'l' : 'p';

            $guru->update($payload);

        } catch (\Exception $e) {
            return to_route('admin.teacher.edit', $guru->id)->with([
                'status' => 'error',
                'type' => 'toast',
                'message' => $e->getMessage()
            ], 500);
        }

        return to_route('admin.teacher.index')->with([
            'status' => 'success',
            'type' => 'toast',
            'message' => 'guru berhasil diubah'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $id)
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
            Teacher::truncate();
            return response()->json(['message' => 'Data guru berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
