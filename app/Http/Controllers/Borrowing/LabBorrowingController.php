<?php

namespace App\Http\Controllers\Borrowing;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\NumberFormatterHelper;
use App\Models\LabBorrowing;
use App\Http\Requests\Borrowing\LabStoreRequest;
use Illuminate\Support\Facades\DB;
use App\Helpers\DateHelper;
use App\Models\Setting;
use App\Helpers\UserHelper;
use App\Http\Controllers\Setting\SettingController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use QrCode;
use App\Notifications\BorrowingStatusChanged;
use App\Traits\Borrowing\LogsBorrowingHistory;

class LabBorrowingController extends Controller
{
    use LogsBorrowingHistory;

    /**
     * Memperbarui status peminjaman berdasarkan waktu
     */
    private function updateLabStatuses()
    {
        $now = Carbon::now();

        // Update status menunggu → kadaluarsa
        $expiredBorrowings = LabBorrowing::where('status', 'menunggu')
            ->where(function ($query) use ($now) {
                $query->where('borrow_date', '<', $now->toDateString())
                    ->orWhere(function ($q) use ($now) {
                        $q->where('borrow_date', '=', $now->toDateString())
                            ->where('end_time', '<', $now->toTimeString());
                    });
            })
            ->get();

        foreach ($expiredBorrowings as $borrowing) {
            $borrowing->status = 'kadaluarsa';
            $borrowing->save();

            // Log history
            $this->logBorrowingHistory(
                $borrowing,
                'kadaluarsa',
                'Peminjaman otomatis kadaluarsa karena melebihi batas waktu'
            );

            // Notify user
            $borrowing->user->notify(new BorrowingStatusChanged($borrowing, 'menunggu'));
        }

        // Logic serupa untuk status lain
    }

    public function getData(Request $request)
    {
        try {

            // $this->updateExpiredBorrowings();

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

    public function index()
    {
        return view('pages.borrowing.lab.management.index');
    }

    public function userBorrowing(Request $request)
    {

        return view('pages.borrowing.lab.requests.index');
    }

    public function listLabBorrowing(Request $request)
    {
        $firstLab = Lab::first();
        if ($firstLab)
            return redirect()->route('borrowing.lab.create', $firstLab->id);

        $now = Carbon::now();

        $borrowedLabIds = LabBorrowing::query()
            ->where('borrow_date', '<=', $now->toDateString())
            ->where('end_time', '<=', $now->toTimeString())
            ->pluck('lab_id');


        Lab::whereIn('id', $borrowedLabIds)->update(['status' => 1]);

        $data = Lab::with('file')->orderBy('status', 'asc')->limit(6)->get();


        $data = $data->map(function ($lab) {
            return [
                'id' => $lab->id,
                'name' => $lab->name,
                'capacity' => NumberFormatterHelper::format($lab->capacity),
                'status' => $lab->status,
                'thumbnail' => $lab->file->path_name ?? '',
                'location' => strlen($lab->location) > 10 ? substr($lab->location, 0, 10) . '...' : $lab->location,
            ];
        });

        return view('pages.borrowing.lab.requests.list', compact('data'));
    }
    public function create(Lab $lab)
    {
        $labData = [
            'id' => $lab->id,
            'name' => $lab->name,
            'status' => $lab->status,
            'facilities' => $lab->facilities,
            'thumbnail' => $lab->file->path_name ?? '',
        ];

        return view('pages.borrowing.lab.requests.create', compact('labData'));
    }

    public function store(LabStoreRequest $request, Lab $lab)
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
                LabBorrowing::create([
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
     * @param string|null $excludeId Exclude this booking ID from conflict check
     * @return bool
     */
    private function hasTimeConflict($labId, $date, $startTime, $endTime, $excludeId = null)
    {
        $query = LabBorrowing::where('lab_id', $labId)
            ->where('borrow_date', $date)
            ->where('status', 'disetujui')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '>=', $startTime)
                        ->where('start_time', '<', $endTime);
                })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('end_time', '>', $startTime)
                            ->where('end_time', '<=', $endTime);
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        // Exclude current booking if needed
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Check if there's a time conflict and return detailed information
     */
    private function getTimeConflictDetails($labId, $date, $startTime, $endTime, $excludeId = null)
    {
        $existingBooking = LabBorrowing::where('lab_id', $labId)
            ->where('borrow_date', $date)
            ->where('status', 'disetujui')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '>=', $startTime)
                        ->where('start_time', '<', $endTime);
                })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('end_time', '>', $startTime)
                            ->where('end_time', '<=', $endTime);
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        // Exclude current booking if needed
        if ($excludeId) {
            $existingBooking->where('id', '!=', $excludeId);
        }

        $booking = $existingBooking->first();

        if ($booking) {
            return [
                'has_conflict' => true,
                'conflict_with' => [
                    'id' => $booking->id,
                    'user' => $booking->user->name,
                    'event' => $booking->event,
                    'time' => Carbon::parse($booking->start_time)->format('H:i') . ' - ' .
                        Carbon::parse($booking->end_time)->format('H:i')
                ]
            ];
        }

        return ['has_conflict' => false];
    }

    public function userBorrowingData(Request $request)
    {
        // Panggil fungsi untuk update status kadaluarsa
        // $this->updateExpiredBorrowings();

        $data = LabBorrowing::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc') // Order by creation date descending
            ->orderBy('borrow_date', 'desc') // Then by borrow date descending
            ->orderBy('start_time', 'desc') // Then by start time descending
            ->get();

        $counter = 0;

        $transformedData = $data->map(function ($b) use (&$counter) {
            return [
                'id' => $b->id,
                'lab' => $b->lab->name,
                'lab_id' => $b->lab_id,
                'borrow_date' => DateHelper::formatLong($b->borrow_date),
                'start_time' => $b->start_time,
                'end_time' => $b->end_time,
                'event' => $b->event,
                'status' => $b->status,
                'number' => ++$counter,
            ];
        });

        return response()->json([
            'data' => $transformedData,
        ]);
    }


    public function show(LabBorrowing $borrowing)
    {
        // Cek kepemilikan borrowing
        // if ($borrowing->user_id !== auth()->id()) {
        //     return response()->json([
        //         'message' => 'Anda tidak memiliki akses ke data ini',
        //     ], 403);
        // }

        $timelineHtml = view('pages.borrowing.lab.requests._timeline', [
            'borrow' => $borrowing
        ])->render();

        $data = [
            'id' => $borrowing->id,
            'lab_name' => $borrowing->lab->name,
            'borrow_date' => Carbon::parse($borrowing->borrow_date)->locale('id')->isoFormat('D MMMM Y'),
            'start_time' => $borrowing->start_time,
            'end_time' => $borrowing->end_time,
            'event' => $borrowing->event,
            'status' => $borrowing->status,
            'notes' => $borrowing->notes,
            'created_at' => $borrowing->created_at->locale('id')->isoFormat('D MMMM Y HH:mm'),
            'borrower' => $borrowing->user->name ?? 'tidak terdefinisi',
        ];

        return response()->json([
            'data' => $data,
            'timelineHtml' => $timelineHtml
        ]);
    }

    /**
     * Membatalkan peminjaman oleh pengguna
     *
     * @param LabBorrowing $borrowing
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(LabBorrowing $borrowing, Request $request)
    {
        // Cek kepemilikan peminjaman
        if ($borrowing->user_id !== auth()->id() && !auth()->user()->hasRole(['admin', 'superadmin'])) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk membatalkan peminjaman ini'
            ], 403);
        }

        // Cek apakah status masih "menunggu" atau "disetujui" (belum dimulai)
        if (!in_array($borrowing->status, ['menunggu', 'disetujui'])) {
            return response()->json([
                'message' => 'Hanya peminjaman dengan status menunggu atau disetujui yang dapat dibatalkan'
            ], 400);
        }

        // Jika status disetujui, cek apakah belum dimulai
        if ($borrowing->status === 'disetujui') {
            $now = Carbon::now();
            $borrowDateTime = Carbon::parse($borrowing->borrow_date . ' ' . $borrowing->start_time);

            if ($borrowDateTime->lte($now)) {
                return response()->json([
                    'message' => 'Peminjaman yang sudah dimulai tidak dapat dibatalkan'
                ], 400);
            }
        }

        try {
            // Mulai transaction
            DB::beginTransaction();

            // Ambil alasan pembatalan jika ada
            $cancellationReason = trim($request->input('notes', ''));
            $notes = !empty($cancellationReason)
                ? $cancellationReason
                : 'Dibatalkan oleh ' . auth()->user()->name . ' pada ' . now()->format('d/m/Y H:i');

            // Update status menjadi "dibatalkan"
            $borrowing->status = 'dibatalkan';
            $borrowing->notes = $notes;
            $borrowing->save();

            // Catat riwayat pembatalan
            $borrowing->histories()->create([
                'user_id' => auth()->id(),
                'status' => 'dibatalkan',
                'notes' => $notes,
                'metadata' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now()->toIso8601String(),
                    'is_admin' => auth()->user()->hasRole(['admin', 'superadmin']) ? 'yes' : 'no'
                ]
            ]);

            // Jika peminjaman sudah disetujui, ubah kembali status lab menjadi tersedia
            if ($borrowing->status === 'disetujui') {
                $borrowing->lab->update(['status' => 'tersedia']);
            }

            DB::commit();

            $borrowing->user->notify(new BorrowingStatusChanged($borrowing, $borrowing->status));

            return response()->json([
                'message' => 'Peminjaman berhasil dibatalkan',
                'data' => [
                    'id' => $borrowing->id,
                    'status' => 'dibatalkan',
                    'cancelledBy' => auth()->user()->name
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Print the borrowing details
     */


    public function print(LabBorrowing $borrowing)
    {
        // Prepare logo as base64
        $logoPath = public_path(settings('logo'));
        $logoBase64 = $this->prepareImageBase64($logoPath);

        // Prepare watermark as base64 (using the same logo but with low opacity)
        $watermarkBase64 = $this->prepareImageBase64($logoPath, 0.1);

        // Generate QR code with verification link
        // $verificationUrl = route('borrowing.verify', ['code' => $borrowing->verification_code]);
        // $qrCode = base64_encode(\QrCode::format('png')->size(200)->generate($verificationUrl));
        $qrCode = null; // Temporarily disabled QR code generation

        $pdf = PDF::loadView('pages.borrowing.lab.print', compact('borrowing', 'logoBase64', 'watermarkBase64', 'qrCode'));
        return $pdf->stream('peminjaman-lab.pdf');
    }

    /**
     * Prepare image as base64 string
     *
     * @param string $imagePath
     * @param float $opacity
     * @return string|null
     */
    private function prepareImageBase64($imagePath, $opacity = 1.0)
    {
        if (!file_exists($imagePath)) {
            return null;
        }

        // For watermark (if opacity < 1), adjust the image
        if ($opacity < 1.0 && function_exists('imagecreatefromstring')) {
            $imageData = file_get_contents($imagePath);
            $source = imagecreatefromstring($imageData);
            $width = imagesx($source);
            $height = imagesy($source);

            // Create transparent image
            $image = imagecreatetruecolor($width, $height);
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $transparency = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefilledrectangle($image, 0, 0, $width, $height, $transparency);

            // Copy original image with reduced opacity
            imagecopymerge($image, $source, 0, 0, 0, 0, $width, $height, $opacity * 100);

            // Output to buffer
            ob_start();
            imagepng($image);
            $imageData = ob_get_clean();

            // Free memory
            imagedestroy($image);
            imagedestroy($source);

            return 'data:image/png;base64,' . base64_encode($imageData);
        }

        // For regular images
        $type = pathinfo($imagePath, PATHINFO_EXTENSION);
        $data = file_get_contents($imagePath);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    public function filter(Request $request)
    {
        $query = LabBorrowing::where('user_id', auth()->id());

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

    /**
     * Menampilkan daftar peminjaman yang menunggu persetujuan
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function pendingApprovals(Request $request)
    {
        $pendingCount = LabBorrowing::where('status', 'menunggu')->count();
        return view('pages.borrowing.lab.management.pending', compact('pendingCount'));
    }

    /**
     * Mendapatkan data peminjaman yang menunggu persetujuan (untuk AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pendingApprovalsData(Request $request)
    {
        $data = LabBorrowing::with(['user', 'lab'])
            ->where('status', 'menunggu')
            ->orderBy('borrow_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        $counter = 0;
        $transformedData = $data->map(function ($b) use (&$counter) {
            return [
                'id' => $b->id,
                'lab' => $b->lab->name,
                'peminjam' => $b->user->name,
                'borrow_date' => DateHelper::formatLong($b->borrow_date),
                'start_time' => $b->start_time,
                'end_time' => $b->end_time,
                'event' => $b->event,
                'status' => $b->status,
                'notes' => $b->notes,
                'created_at' => $b->created_at->diffForHumans(),
                'number' => ++$counter,
            ];
        });

        return response()->json([
            'data' => $transformedData,
        ]);
    }

    /**
     * Menyetujui peminjaman
     *
     * @param LabBorrowing $borrowing
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(LabBorrowing $borrowing, Request $request)
    {
        try {
            // Verifikasi apakah peminjaman masih berstatus menunggu
            if ($borrowing->status !== 'menunggu') {
                return response()->json([
                    'message' => 'Peminjaman sudah tidak dalam status menunggu persetujuan'
                ], 422);
            }

            // Periksa konflik waktu
            $borrowDate = $borrowing->borrow_date;
            $startTime = $borrowing->start_time;
            $endTime = $borrowing->end_time;

            if ($this->hasTimeConflict($borrowing->lab_id, $borrowDate, $startTime, $endTime, $borrowing->id)) {
                return response()->json([
                    'message' => 'Terdapat konflik waktu dengan peminjaman lain yang sudah disetujui'
                ], 422);
            }

            // Gunakan transaction untuk memastikan integritas data
            DB::beginTransaction();

            // Ambil catatan jika ada
            $notes = trim($request->input('notes', ''));

            // Update status peminjaman
            $borrowing->status = 'disetujui';
            if (!empty($notes)) {
                $borrowing->notes = $notes;
            }
            $borrowing->save();

            // Catat riwayat status
            $borrowing->histories()->create([
                'user_id' => auth()->id(),
                'status' => 'disetujui',
                'notes' => !empty($notes) ? $notes : 'Disetujui oleh ' . auth()->user()->name,
                'metadata' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now()->toIso8601String()
                ]
            ]);

            // Jika peminjaman untuk hari ini, ubah status lab menjadi tidak tersedia
            $today = Carbon::now()->format('Y-m-d');
            $now = Carbon::now()->format('H:i:s');

            if ($borrowDate == $today && $startTime <= $now && $endTime >= $now) {
                $borrowing->lab->update(['status' => 'tidak tersedia']);
            }

            // Kirim notifikasi (bisa diimplementasikan sesuai kebutuhan)
            // Contoh: NotificationService::sendApprovalNotification($borrowing);

            DB::commit();

            $borrowing->user->notify(new BorrowingStatusChanged($borrowing, $borrowing->status));

            return response()->json([
                'message' => 'Peminjaman berhasil disetujui',
                'data' => [
                    'id' => $borrowing->id,
                    'status' => 'disetujui',
                    'updated_at' => $borrowing->updated_at->diffForHumans(),
                    'approver' => auth()->user()->name
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menolak peminjaman dengan alasan
     *
     * @param LabBorrowing $borrowing
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(LabBorrowing $borrowing, Request $request)
    {
        // Validasi request
        $request->validate([
            'notes' => 'required|string|min:5|max:500'
        ], [
            'notes.required' => 'Alasan penolakan wajib diisi',
            'notes.min' => 'Alasan penolakan minimal 5 karakter',
            'notes.max' => 'Alasan penolakan maksimal 500 karakter'
        ]);

        try {
            // Verifikasi apakah peminjaman masih berstatus menunggu
            if ($borrowing->status !== 'menunggu') {
                return response()->json([
                    'message' => 'Peminjaman sudah tidak dalam status menunggu persetujuan'
                ], 422);
            }

            // Gunakan transaction untuk memastikan integritas data
            DB::beginTransaction();

            // Ambil alasan penolakan dari request
            $rejectionReason = $request->input('notes');

            // Update status peminjaman
            $borrowing->status = 'ditolak';
            $borrowing->notes = $rejectionReason;
            $borrowing->save();

            // Catat riwayat status
            $borrowing->histories()->create([
                'user_id' => auth()->id(),
                'status' => 'ditolak',
                'notes' => $rejectionReason,
                'metadata' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now()->toIso8601String()
                ]
            ]);

            // Kirim notifikasi (bisa diimplementasikan sesuai kebutuhan)
            // Contoh: NotificationService::sendRejectionNotification($borrowing);

            DB::commit();

            $borrowing->user->notify(new BorrowingStatusChanged($borrowing, $borrowing->status));

            return response()->json([
                'message' => 'Peminjaman berhasil ditolak',
                'data' => [
                    'id' => $borrowing->id,
                    'status' => 'ditolak',
                    'reason' => $rejectionReason,
                    'rejectedBy' => auth()->user()->name,
                    'updated_at' => $borrowing->updated_at->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menandai peminjaman sebagai selesai
     *
     * @param LabBorrowing $borrowing
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(LabBorrowing $borrowing, Request $request)
    {
        try {
            // Verifikasi apakah peminjaman berstatus disetujui
            if ($borrowing->status !== 'disetujui' && $borrowing->status !== 'digunakan') {
                return response()->json([
                    'message' => 'Hanya peminjaman dengan status disetujui atau digunakan yang dapat diselesaikan'
                ], 422);
            }

            // Gunakan transaction
            DB::beginTransaction();

            // Ambil catatan jika ada
            $notes = trim($request->input('notes', ''));
            $completionNotes = !empty($notes)
                ? $notes
                : 'Diselesaikan oleh ' . auth()->user()->name . ' pada ' . now()->format('d/m/Y H:i');

            // Update status peminjaman
            $borrowing->status = 'selesai';
            $borrowing->notes = $completionNotes;
            $borrowing->save();

            // Catat riwayat status
            $borrowing->histories()->create([
                'user_id' => auth()->id(),
                'status' => 'selesai',
                'notes' => $completionNotes,
                'metadata' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now()->toIso8601String()
                ]
            ]);

            // Ubah status lab menjadi tersedia
            $borrowing->lab->update(['status' => 'tersedia']);

            DB::commit();

            $borrowing->user->notify(new BorrowingStatusChanged($borrowing, $borrowing->status));

            return response()->json([
                'message' => 'Peminjaman berhasil diselesaikan',
                'data' => [
                    'id' => $borrowing->id,
                    'status' => 'selesai',
                    'completedBy' => auth()->user()->name,
                    'lab_name' => $borrowing->lab->name,
                    'updated_at' => $borrowing->updated_at->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan halaman riwayat aktivitas peminjaman
     *
     * @param LabBorrowing $borrowing
     * @return \Illuminate\View\View
     */
    public function showHistory(LabBorrowing $borrowing)
    {
        // Verifikasi akses - hanya admin atau pemilik
        if (!auth()->user()->hasRole(['admin', 'superadmin']) && $borrowing->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke data ini');
        }

        // Ambil semua riwayat perubahan status
        $histories = $borrowing->histories()
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($history) {
                return [
                    'id' => $history->id,
                    'status' => $history->status,
                    'notes' => $history->notes,
                    'user' => $history->user ? $history->user->name : 'Sistem',
                    'timestamp' => $history->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY, HH:mm:ss'),
                    'time_ago' => $history->created_at->diffForHumans(),
                    'metadata' => $history->metadata
                ];
            });

        return view('pages.borrowing.lab.requests.history', [
            'borrowing' => $borrowing,
            'histories' => $histories
        ]);
    }

    /**
     * Menampilkan form untuk mengajukan ulang peminjaman yang ditolak
     *
     * @param Lab $lab
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function resubmit(Lab $lab, Request $request)
    {

        // Validasi referensi
        $referenceId = $request->query('reference');
        if (!$referenceId) {
            return redirect()->route('borrowing.lab.list')
                ->with('error', 'Referensi peminjaman tidak ditemukan');
        }

        // Ambil data peminjaman sebelumnya
        $reference = LabBorrowing::where('id', $referenceId)
            ->where('user_id', auth()->id())
            ->where('lab_id', $lab->id)
            ->where('status', 'ditolak') // Pastikan hanya yang ditolak yang bisa diajukan ulang
            ->first();

        if (!$reference) {
            return redirect()->route('borrowing.lab.list')
                ->with('error', 'Peminjaman yang akan diajukan ulang tidak ditemukan atau tidak dapat diajukan ulang');
        }

        // Siapkan data untuk form
        $labData = [
            'id' => $lab->id,
            'name' => $lab->name,
            'status' => $lab->status,
            'facilities' => $lab->facilities,
            'thumbnail' => $lab->file->path_name ?? '',
        ];

        // Data peminjaman sebelumnya untuk mengisi form
        $previousData = [
            'borrow_date' => $reference->borrow_date,
            'start_time' => $reference->start_time,
            'end_time' => $reference->end_time,
            'event' => $reference->event,
            'notes' => $reference->notes,
            'rejection_reason' => $reference->notes, // Alasan penolakan
            'reference_id' => $reference->id
        ];

        return view('pages.borrowing.lab.requests.resubmit', compact('labData', 'previousData'));
    }

    /**
     * Menyimpan pengajuan ulang peminjaman
     *
     * @param Lab $lab
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resubmitStore(Lab $lab, Request $request)
    {
        // Validasi input sama seperti store normal
        $validatedData = $request->validate([
            'borrow_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'event' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'reference_id' => 'required|exists:lab_borrowings,id'
        ]);

        // Ambil referensi
        $reference = LabBorrowing::where('id', $validatedData['reference_id'])
            ->where('user_id', auth()->id())
            ->where('status', 'ditolak')
            ->first();

        if (!$reference) {
            return response()->json([
                'message' => 'Referensi peminjaman tidak valid'
            ], 422);
        }

        // Periksa konflik waktu
        $borrowDate = Carbon::parse($validatedData['borrow_date'])->format('Y-m-d');
        if ($this->hasTimeConflict($lab->id, $borrowDate, $validatedData['start_time'], $validatedData['end_time'])) {
            return response()->json([
                'message' => 'Waktu yang dipilih sudah dipesan. Silahkan pilih waktu lain.'
            ], 422);
        }

        try {
            // Gunakan transaction
            DB::beginTransaction();

            // Buat peminjaman baru
            $newBorrowing = LabBorrowing::create([
                'user_id' => auth()->id(),
                'lab_id' => $lab->id,
                'borrow_date' => $borrowDate,
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'event' => $validatedData['event'],
                'notes' => $validatedData['notes'] ?? null,
                'status' => 'menunggu'
            ]);

            // Catat riwayat
            // Jika tabel history sudah dibuat
            if (class_exists('App\Models\BorrowingHistory')) {
                $newBorrowing->histories()->create([
                    'user_id' => auth()->id(),
                    'status' => 'menunggu',
                    'notes' => 'Pengajuan ulang dari peminjaman ' . $reference->id,
                    'metadata' => [
                        'reference_id' => $reference->id,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Pengajuan ulang peminjaman berhasil dikirim',
                'data' => [
                    'id' => $newBorrowing->id
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check lab availability without creating a booking
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'borrow_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'exclude_id' => 'nullable|exists:lab_borrowings,id',
        ]);

        $conflict = $this->getTimeConflictDetails(
            $request->lab_id,
            Carbon::parse($request->borrow_date)->format('Y-m-d'),
            $request->start_time,
            $request->end_time,
            $request->exclude_id
        );

        if ($conflict['has_conflict']) {
            return response()->json([
                'available' => false,
                'conflict' => $conflict['conflict_with']
            ]);
        }

        return response()->json(['available' => true]);
    }
}
