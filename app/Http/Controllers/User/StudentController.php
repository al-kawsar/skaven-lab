<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Student\StoreRequest;
use App\Http\Requests\Student\UpdateRequest;
use App\Models\Student;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Exports\SiswaImportTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{

    public function index(Request $request)
    {
        $data['totalData'] = Student::count();
        return view('pages.student.index', compact('data'));
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
        $query = Student::query();

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
        $recordsTotal = Student::count();
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


    public function create()
    {
        return view('pages.student.create');
    }


    public function store(StoreRequest $request)
    {
        try {
            $payload = $request->validated();

            $payload['jenis_kelamin'] = $payload['jenis_kelamin'] == 1 ? 'l' : 'p';

            // Model akan otomatis menangani konversi format tanggal lewat mutator
            Student::create($payload);

            return $this->sendSuccessResponse($request, 'Siswa berhasil ditambahkan');
        } catch (\Exception $e) {
            return $this->sendServerErrorResponse($request, $e->getMessage(), $e);
        }
    }


    public function show(Student $siswa)
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

        return view('pages.student.show', compact('data'));
    }


    public function edit(Student $siswa)
    {
        // Sistem akan otomatis menggunakan accessor untuk format tanggal
        return view('pages.student.edit', compact('siswa'));
    }


    public function update(UpdateRequest $request, Student $siswa)
    {
        try {
            $payload = $request->validated();

            $payload['jenis_kelamin'] = $payload['jenis_kelamin'] == 1 ? 'l' : 'p';

            // Model akan otomatis menangani konversi format tanggal lewat mutator
            $siswa->update($payload);

            return to_route('student.index')->with([
                'status' => 'success',
                'type' => 'toast',
                'message' => 'Siswa berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return to_route('student.edit', $siswa->id)->with([
                'status' => 'error',
                'type' => 'toast',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, Student $siswa)
    {
        try {
            $siswa->delete();
            return $this->sendSuccessResponse($request, 'Data siswa berhasil dihapus');
        } catch (\Exception $e) {
            return $this->sendServerErrorResponse($request, $e->getMessage(), $e);
        }
    }

    public function destroyAll(Request $request)
    {
        try {
            Student::truncate();
            return $this->sendSuccessResponse($request, 'Data siswa berhasil dihapus');
        } catch (\Exception $e) {
            return $this->sendServerErrorResponse($request, $e->getMessage(), $e);
        }
    }

    public function filterByGender(Request $request, $gender)
    {
        $length = $request->input('length', 10);
        $start = $request->input('start', 0);


        $data = Student::jenisKelamin($gender)
            ->orderBy('created_at', 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        $totalData = Student::jenisKelamin($gender)->count();

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
        $query = Student::query();

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

                $count = Student::where('tgl_lahir', '<=', $maxDate)
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

                $count = Student::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

                $statisticsByRegistrationMonth[] = [
                    'label' => $month->format('M Y'),
                    'value' => $count
                ];
            }

            // Reverse the array to get chronological order
            $statisticsByRegistrationMonth = array_reverse($statisticsByRegistrationMonth);

            return $this->sendSuccessResponse($request, 'Statistics generated successfully', [
                'by_gender' => $statisticsByGender,
                'by_religion' => $statisticsByReligion,
                'by_age' => $statisticsByAge,
                'by_birth_month' => $statisticsByBirthMonth,
                'by_registration_month' => $statisticsByRegistrationMonth ?? [],
                'summary' => [
                    'total' => Student::count(),
                    'male' => Student::where('jenis_kelamin', 'l')->count(),
                    'female' => Student::where('jenis_kelamin', 'p')->count(),
                    'has_complete_data' => Student::whereNotNull('name')
                        ->whereNotNull('nis')
                        ->whereNotNull('nisn')
                        ->whereNotNull('alamat')
                        ->whereNotNull('jenis_kelamin')
                        ->whereNotNull('agama')
                        ->whereNotNull('tgl_lahir')
                        ->count(),
                    'added_this_month' => Student::where('created_at', '>=', now()->startOfMonth())->count(),
                ]
            ]);
        } catch (\Exception $e) {
            return $this->sendServerErrorResponse($request, 'Error generating statistics: ' . $e->getMessage(), $e);
        }
    }


    public function statisticsView()
    {
        // Get summary data for display at the top of the page
        $summary = [
            'total' => Student::count(),
            'male' => Student::where('jenis_kelamin', 'l')->count(),
            'female' => Student::where('jenis_kelamin', 'p')->count(),
            'added_this_month' => Student::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('pages.student.statistics', compact('summary'));
    }

    public function importFromExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationErrorResponse(
                $request,
                'Validasi file gagal',
                $validator->errors()->toArray()
            );
        }

        try {
            Excel::import(new SiswaImport, $request->file('file'));

            return $this->sendSuccessResponse(
                $request,
                'Data siswa berhasil diimport'
            );
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return $this->sendValidationErrorResponse(
                $request,
                'Validasi data gagal',
                $e->failures()
            );
        } catch (\Exception $e) {
            return $this->sendServerErrorResponse(
                $request,
                'Import gagal: ' . $e->getMessage(),
                $e
            );
        }
    }

    public function downloadImportTemplate()
    {
        return Excel::download(new SiswaImportTemplate, "template_import_siswa_" . time() . ".xlsx");
    }

}
