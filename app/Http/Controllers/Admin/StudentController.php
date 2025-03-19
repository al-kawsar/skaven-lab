<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Student\StoreRequest;
use App\Http\Requests\Student\UpdateRequest;
use App\Models\Siswa;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Exports\SiswaImportTemplate;

class StudentController extends Controller
{

    public function index(Request $request)
    {
        $data['totalData'] = Siswa::count();
        return view('pages.admin.student.index', compact('data'));
    }

    public function getData(Request $request)
    {
        $length = $request->input('length', 10);
        $start = $request->input('start', 0);
        $draw = $request->input('draw', 1);
        $searchValue = $request->input('search_value', '');
        $jenisKelamin = $request->input('jenis_kelamin', '');

        // Filter tambahan
        $agama = $request->input('agama', '');
        $usiaMin = $request->input('usia_min');
        $usiaMax = $request->input('usia_max');
        $initial = $request->input('initial', '');
        $sortBy = $request->input('sort_by', 'name');
        $sortDir = $request->input('sort_dir', 'asc');

        // Filter lanjutan
        $tglLahirStart = $request->input('tgl_lahir_start');
        $tglLahirEnd = $request->input('tgl_lahir_end');
        $bulanLahir = $request->input('bulan_lahir');
        $hasFoto = $request->input('has_foto');
        $completeData = $request->input('complete_data');
        $alamat = $request->input('alamat');

        // Buat query dasar
        $query = Siswa::query();

        // Terapkan filter jenis kelamin
        if ($jenisKelamin && $jenisKelamin != ' ') {
            $query->where('jenis_kelamin', $jenisKelamin);
        }

        // Terapkan filter agama
        if ($agama) {
            $query->where('agama', $agama);
        }

        // Terapkan filter usia
        if ($usiaMin || $usiaMax) {
            // Konversi usia ke tanggal lahir
            $today = Carbon::today();

            if ($usiaMin) {
                $maxBirthDate = $today->copy()->subYears($usiaMin);
                $query->where('tgl_lahir', '<=', $maxBirthDate->format('Y-m-d'));
            }

            if ($usiaMax) {
                $minBirthDate = $today->copy()->subYears($usiaMax + 1)->addDay();
                $query->where('tgl_lahir', '>=', $minBirthDate->format('Y-m-d'));
            }
        }

        // Terapkan filter huruf awal
        if ($initial) {
            $query->where('name', 'like', $initial . '%');
        }

        // Terapkan filter tanggal lahir
        if ($tglLahirStart) {
            $query->whereDate('tgl_lahir', '>=', $tglLahirStart);
        }

        if ($tglLahirEnd) {
            $query->whereDate('tgl_lahir', '<=', $tglLahirEnd);
        }

        // Terapkan filter bulan lahir
        if ($bulanLahir) {
            $query->whereRaw('MONTH(tgl_lahir) = ?', [$bulanLahir]);
        }

        // Terapkan filter has foto
        if ($hasFoto) {
            $query->whereNotNull('foto_siswa');
        }

        // Terapkan filter data lengkap
        if ($completeData) {
            $query->whereNotNull('name')
                ->whereNotNull('nis')
                ->whereNotNull('nisn')
                ->whereNotNull('alamat')
                ->whereNotNull('jenis_kelamin')
                ->whereNotNull('agama')
                ->whereNotNull('tgl_lahir');
        }

        // Terapkan filter alamat
        if ($alamat) {
            $query->where('alamat', 'like', "%{$alamat}%");
        }

        // Terapkan pencarian
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('nis', 'like', "%{$searchValue}%")
                    ->orWhere('nisn', 'like', "%{$searchValue}%");
            });
        }

        // Hitung total data dan total data setelah filter
        $recordsTotal = Siswa::count();
        $recordsFiltered = $query->count();

        // Sorting
        if ($sortBy == 'usia') {
            // Untuk usia, kita perlu mengurutkan berdasarkan tanggal lahir (terbalik)
            $direction = $sortDir == 'asc' ? 'desc' : 'asc';
            $query->orderBy('tgl_lahir', $direction);
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        // Ambil data dengan pagination
        $data = $query->offset($start)
            ->limit($length)
            ->get();

        $counter = $start;

        // Transform data
        $transformedData = $data->map(function ($siswa) use (&$counter) {
            return [
                'id' => $siswa->id,
                'name' => $siswa->name,
                'nis' => $siswa->nis,
                'nisn' => $siswa->nisn,
                'alamat' => $siswa->alamat,
                'jenis_kelamin' => $siswa->jenis_kelamin_lengkap,
                'agama' => $siswa->agama,
                'tanggal_lahir' => $siswa->tgl_lahir,
                'usia' => $siswa->usia . ' tahun',
                'created_at' => $siswa->created_at->format('d M Y'),
                'foto_url' => $siswa->foto_url,
                'number' => ++$counter,
            ];
        });

        // Return format yang dibutuhkan oleh DataTables
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $transformedData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $payload = $request->validated();

            $payload['jenis_kelamin'] = $payload['jenis_kelamin'] == 1 ? 'l' : 'p';

            // Model akan otomatis menangani konversi format tanggal lewat mutator
            Siswa::create($payload);

            return response()->json([
                'message' => 'Siswa berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        // Menampilkan detail siswa dengan memanfaatkan accessor dari model
        $data = [
            'id' => $siswa->id,
            'name' => $siswa->name,
            'nis' => $siswa->nis,
            'nisn' => $siswa->nisn,
            'alamat' => $siswa->alamat,
            'jenis_kelamin' => $siswa->jenis_kelamin_lengkap,
            'agama' => $siswa->agama,
            'tanggal_lahir' => $siswa->tgl_lahir,
            'usia' => $siswa->usia . ' tahun',
            'foto_url' => $siswa->foto_url,
        ];

        return view('pages.admin.student.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        // Sistem akan otomatis menggunakan accessor untuk format tanggal
        return view('pages.admin.student.edit', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Siswa $siswa)
    {
        try {
            $payload = $request->validated();

            $payload['jenis_kelamin'] = $payload['jenis_kelamin'] == 1 ? 'l' : 'p';

            // Model akan otomatis menangani konversi format tanggal lewat mutator
            $siswa->update($payload);

            return to_route('admin.student.index')->with([
                'status' => 'success',
                'type' => 'toast',
                'message' => 'Siswa berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return to_route('admin.student.edit', $siswa->id)->with([
                'status' => 'error',
                'type' => 'toast',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        try {
            $siswa->delete();
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

    /**
     * Menampilkan siswa berdasarkan filter jenis kelamin
     */
    public function filterByGender(Request $request, $gender)
    {
        $length = $request->input('length', 10);
        $start = $request->input('start', 0);


        $data = Siswa::jenisKelamin($gender)
            ->orderBy('created_at', 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        $totalData = Siswa::jenisKelamin($gender)->count();

        // Transformasi data seperti di method getData
        $counter = $start;
        $transformedData = $data->map(function ($siswa) use (&$counter) {
            return [
                'id' => $siswa->id,
                'name' => $siswa->name,
                'nis' => $siswa->nis,
                'nisn' => $siswa->nisn,
                'alamat' => $siswa->alamat,
                'jenis_kelamin' => $siswa->jenis_kelamin_lengkap,
                'agama' => $siswa->agama,
                'tanggal_lahir' => $siswa->tgl_lahir,
                'usia' => $siswa->usia . ' tahun',
                'created_at' => $siswa->created_at->format('d M Y'),
                'foto_url' => $siswa->foto_url,
                'number' => ++$counter,
            ];
        });

        return response()->json([
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $transformedData,
        ]);
    }

    /**
     * Export data siswa ke Excel
     */
    public function exportToExcel(Request $request)
    {
        // Ambil filter yang sama dengan getData
        $jenisKelamin = $request->input('jenis_kelamin', '');
        $agama = $request->input('agama', '');
        $usiaMin = $request->input('usia_min');
        $usiaMax = $request->input('usia_max');
        $initial = $request->input('initial', '');
        $searchValue = $request->input('search_value', '');
        $tglLahirStart = $request->input('tgl_lahir_start');
        $tglLahirEnd = $request->input('tgl_lahir_end');
        $bulanLahir = $request->input('bulan_lahir');
        $alamat = $request->input('alamat');

        // Buat query
        $query = Siswa::query();

        // Terapkan filter yang sama seperti di getData()
        if ($jenisKelamin && $jenisKelamin != ' ') {
            $query->where('jenis_kelamin', $jenisKelamin);
        }

        if ($agama) {
            $query->where('agama', $agama);
        }

        // Terapkan filter usia
        if ($usiaMin || $usiaMax) {
            $today = Carbon::today();

            if ($usiaMin) {
                $maxBirthDate = $today->copy()->subYears($usiaMin);
                $query->where('tgl_lahir', '<=', $maxBirthDate->format('Y-m-d'));
            }

            if ($usiaMax) {
                $minBirthDate = $today->copy()->subYears($usiaMax + 1)->addDay();
                $query->where('tgl_lahir', '>=', $minBirthDate->format('Y-m-d'));
            }
        }

        // Filter tambahan
        if ($initial) {
            $query->where('name', 'like', $initial . '%');
        }

        if ($tglLahirStart) {
            $query->whereDate('tgl_lahir', '>=', $tglLahirStart);
        }

        if ($tglLahirEnd) {
            $query->whereDate('tgl_lahir', '<=', $tglLahirEnd);
        }

        if ($bulanLahir) {
            $query->whereRaw('MONTH(tgl_lahir) = ?', [$bulanLahir]);
        }

        if ($alamat) {
            $query->where('alamat', 'like', "%{$alamat}%");
        }

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('nis', 'like', "%{$searchValue}%")
                    ->orWhere('nisn', 'like', "%{$searchValue}%");
            });
        }

        // Dapatkan data
        $siswaData = $query->get();

        // Generate filename with timestamp
        $filename = 'daftar_siswa_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Generate Excel menggunakan Laravel Excel
        return Excel::download(new SiswaExport($siswaData), $filename);
    }

    /**
     * Statistik Siswa - Mendapatkan data statistik dalam format JSON
     */
    public function getStatistics(Request $request)
    {
        try {
            // Jumlah siswa per jenis kelamin
            $statisticsByGender = DB::table('students')
                ->select('jenis_kelamin', DB::raw('count(*) as total'))
                ->groupBy('jenis_kelamin')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->jenis_kelamin == 'l' ? 'Laki-laki' : 'Perempuan',
                        'value' => $item->total
                    ];
                });

            // Jumlah siswa per agama
            $statisticsByReligion = DB::table('students')
                ->select('agama', DB::raw('count(*) as total'))
                ->whereNotNull('agama')
                ->groupBy('agama')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->agama ?: 'Tidak Diketahui',
                        'value' => $item->total
                    ];
                });

            // Jumlah siswa per kelompok usia
            $ageGroups = [
                '6-10 tahun' => [6, 10],
                '11-15 tahun' => [11, 15],
                '16-20 tahun' => [16, 20],
                '> 20 tahun' => [21, 100]
            ];

            $statisticsByAge = [];

            foreach ($ageGroups as $label => $range) {
                $minAge = $range[0];
                $maxAge = $range[1];

                $today = Carbon::today();
                $maxDate = $today->copy()->subYears($minAge)->format('Y-m-d');
                $minDate = $today->copy()->subYears($maxAge + 1)->addDay()->format('Y-m-d');

                $count = Siswa::where('tgl_lahir', '<=', $maxDate)
                    ->where('tgl_lahir', '>=', $minDate)
                    ->count();

                $statisticsByAge[] = [
                    'label' => $label,
                    'value' => $count
                ];
            }

            // Bulan lahir terbanyak
            $statisticsByBirthMonth = DB::table('students')
                ->select(DB::raw('MONTH(tgl_lahir) as bulan'), DB::raw('count(*) as total'))
                ->whereNotNull('tgl_lahir')
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get()
                ->map(function ($item) {
                    $bulanNames = [
                        'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                    ];

                    return [
                        'label' => $bulanNames[$item->bulan - 1],
                        'value' => $item->total
                    ];
                });

            // Tambahan: Statistik penambahan siswa per bulan (12 bulan terakhir)
            $lastYear = now()->subYear();
            $statisticsByRegistrationMonth = [];

            for ($i = 0; $i < 12; $i++) {
                $month = now()->subMonths($i);
                $startOfMonth = $month->copy()->startOfMonth();
                $endOfMonth = $month->copy()->endOfMonth();

                $count = Siswa::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

                $statisticsByRegistrationMonth[] = [
                    'label' => $month->format('M Y'),
                    'value' => $count
                ];
            }

            // Reverse the array to get chronological order
            $statisticsByRegistrationMonth = array_reverse($statisticsByRegistrationMonth);

            return response()->json([
                'by_gender' => $statisticsByGender,
                'by_religion' => $statisticsByReligion,
                'by_age' => $statisticsByAge,
                'by_birth_month' => $statisticsByBirthMonth,
                'by_registration_month' => $statisticsByRegistrationMonth ?? [],
                'summary' => [
                    'total' => Siswa::count(),
                    'male' => Siswa::where('jenis_kelamin', 'l')->count(),
                    'female' => Siswa::where('jenis_kelamin', 'p')->count(),
                    'has_complete_data' => Siswa::whereNotNull('name')
                        ->whereNotNull('nis')
                        ->whereNotNull('nisn')
                        ->whereNotNull('alamat')
                        ->whereNotNull('jenis_kelamin')
                        ->whereNotNull('agama')
                        ->whereNotNull('tgl_lahir')
                        ->count(),
                    'added_this_month' => Siswa::where('created_at', '>=', now()->startOfMonth())->count(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Error generating statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan halaman statistik
     */
    public function statisticsView()
    {
        // Get summary data for display at the top of the page
        $summary = [
            'total' => Siswa::count(),
            'male' => Siswa::where('jenis_kelamin', 'l')->count(),
            'female' => Siswa::where('jenis_kelamin', 'p')->count(),
            'added_this_month' => Siswa::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('pages.admin.student.statistics', compact('summary'));
    }

    /**
     * Import data siswa dari Excel
     */
    public function importFromExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diimport'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $e->getMessage(),
                'errors' => $e->failures()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template untuk import data
     */
    public function downloadImportTemplate()
    {
        return Excel::download(new SiswaImportTemplate, 'template_import_siswa.xlsx');
    }

}
