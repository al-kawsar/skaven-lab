<?php

namespace App\Http\Controllers\Borrowing;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\LabBorrowing;
use App\Http\Requests\Borrowing\LabStoreRequest;
use Illuminate\Support\Facades\DB;
use App\Helpers\DateHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\BorrowingStatusChanged;
use App\Traits\Borrowing\LogsBorrowingHistory;
use App\Notifications\BorrowingCanceled;
use Illuminate\Support\Facades\Log;

class LabBorrowingController extends Controller
{
    use LogsBorrowingHistory;

    /**
     * Memperbarui status peminjaman berdasarkan waktu
     */
    private function updateLabStatuses()
    {
        Log::info('LabBorrowingController::updateLabStatuses - Starting status update process');
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

        Log::info('LabBorrowingController::updateLabStatuses - Found ' . $expiredBorrowings->count() . ' expired borrowings');

        foreach ($expiredBorrowings as $borrowing) {
            Log::debug('LabBorrowingController::updateLabStatuses - Processing expired borrowing', [
                'id' => $borrowing->id,
                'borrow_code' => $borrowing->borrow_code,
                'user_id' => $borrowing->user_id,
                'borrow_date' => $borrowing->borrow_date
            ]);

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

            Log::info('LabBorrowingController::updateLabStatuses - Borrowing marked as expired', [
                'id' => $borrowing->id,
                'borrow_code' => $borrowing->borrow_code
            ]);
        }

        // Logic serupa untuk status lain
    }
    public function getData(Request $request)
    {
        Log::info('LabBorrowingController::getData - Fetching borrowing data', [
            'filters' => $request->only(['status', 'start_date', 'end_date']),
            'user_id' => auth()->id()
        ]);

        try {
            // Base query
            $query = LabBorrowing::query();

            // Apply filters
            if ($request->status) {
                $query->where('status', $request->status);
                Log::debug('LabBorrowingController::getData - Filtering by status', ['status' => $request->status]);
            }

            if ($request->start_date) {
                $query->where('borrow_date', '>=', $request->start_date);
                Log::debug('LabBorrowingController::getData - Filtering by start_date', ['start_date' => $request->start_date]);
            }

            if ($request->end_date) {
                $query->where('borrow_date', '<=', $request->end_date);
                Log::debug('LabBorrowingController::getData - Filtering by end_date', ['end_date' => $request->end_date]);
            }

            // Prioritize bookings based on status and date
            $query->orderByRaw('
                CASE
                    -- Prioritize active bookings
                    WHEN status = "menunggu" AND borrow_date >= CURRENT_DATE THEN 1
                    WHEN status = "disetujui" AND borrow_date >= CURRENT_DATE THEN 2

                    -- Then show completed and rejected recent bookings
                    WHEN status = "selesai" AND borrow_date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) THEN 3
                    WHEN status = "ditolak" AND borrow_date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) THEN 4
                    WHEN status = "dibatalkan" AND borrow_date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) THEN 5

                    -- Then older pending and approved
                    WHEN status = "menunggu" THEN 6
                    WHEN status = "disetujui" THEN 7

                    -- Then older completed and rejected
                    WHEN status = "selesai" THEN 8
                    WHEN status = "ditolak" THEN 9
                    WHEN status = "dibatalkan" THEN 10
                    WHEN status = "kadaluarsa" THEN 11

                    -- Fallback for any other status
                    ELSE 12
                END
            ');

            // For items with the same priority, sort by date
            $query->orderBy(DB::raw('
                CASE
                    WHEN status IN ("menunggu", "disetujui") THEN borrow_date
                    ELSE borrow_date
                END
            '));

            // Add separate ordering for descending dates for non-pending/approved statuses
            $query->orderBy(DB::raw('
                CASE
                    WHEN status NOT IN ("menunggu", "disetujui") THEN borrow_date
                    ELSE NULL
                END
            '), 'DESC');

            // Then by time of day
            $query->orderBy('start_time', 'asc');

            // Finally by creation date if everything else is equal
            $query->orderBy('created_at', 'desc');

            $data = $query->get();
            Log::info('LabBorrowingController::getData - Retrieved ' . $data->count() . ' borrowing records');

            if ($data->isEmpty()) {
                Log::debug('LabBorrowingController::getData - No data found with applied filters');
                return response()->json([
                    'data' => []
                ]);
            }

            $counter = 0;

            $transformedData = $data->map(function ($b) use (&$counter) {
                return [
                    'id' => $b->id,
                    'peminjam' => $b->user->name,
                    'borrow_date' => Carbon::parse($b->borrow_date)->format('F j, Y'),
                    'start_time' => $b->start_time,
                    'end_time' => $b->end_time,
                    'event' => $b->event,
                    'status' => $b->status,
                    'number' => ++$counter,
                    'borrow_code' => $b->borrow_code ?? '-',
                    'is_recurring' => $b->is_recurring ? true : false,
                    'parent_booking_id' => $b->parent_booking_id,
                ];
            });

            return response()->json([
                'data' => $transformedData,
            ]);
        } catch (\Exception $e) {
            Log::error('LabBorrowingController::getData - Error fetching borrowing data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filters' => $request->only(['status', 'start_date', 'end_date'])
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        Log::info('LabBorrowingController::index - Accessing lab borrowing management page', [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name
        ]);
        return view('pages.borrowing.lab.management.index');
    }

    public function userBorrowing(Request $request)
    {
        Log::info('LabBorrowingController::userBorrowing - Accessing user borrowing page', [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name
        ]);
        return view('pages.borrowing.lab.requests.index');
    }

    public function create()
    {
        Log::info('LabBorrowingController::create - Accessing create borrowing form', [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name
        ]);
        return view('pages.borrowing.lab.requests.create');
    }

    public function store(LabStoreRequest $request)
    {
        Log::info('LabBorrowingController::store - Processing new borrowing request', [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'is_recurring' => $request->has('is_recurring') && $request->is_recurring == 1,
            'borrow_date' => $request->borrow_date
        ]);

        try {
            $payload = $request->validated();
            $borrowDate = Carbon::parse($payload['borrow_date'])->format('Y-m-d');
            $isRecurring = $request->has('is_recurring') && $request->is_recurring == 1;

            // Jika bukan peminjaman berulang, gunakan proses normal
            if (!$isRecurring) {
                Log::debug('LabBorrowingController::store - Processing non-recurring borrowing', [
                    'borrow_date' => $borrowDate,
                    'start_time' => $payload['start_time'],
                    'end_time' => $payload['end_time']
                ]);

                // Check for time conflicts
                if ($this->hasTimeConflict($borrowDate, $payload['start_time'], $payload['end_time'])) {
                    Log::warning('LabBorrowingController::store - Time conflict detected', [
                        'borrow_date' => $borrowDate,
                        'start_time' => $payload['start_time'],
                        'end_time' => $payload['end_time']
                    ]);

                    return response()->json([
                        'message' => 'Waktu yang dipilih sudah dipesan. Silahkan pilih waktu lain.',
                    ], 422);
                }

                // Proses normal untuk non-recurring
                return DB::transaction(function () use ($payload, $borrowDate) {
                    // Generate kode peminjaman terlebih dahulu
                    $borrowCode = $this->generateBorrowingCode();
                    Log::debug('LabBorrowingController::store - Generated borrow code', ['borrow_code' => $borrowCode]);

                    $borrowing = LabBorrowing::create([
                        'user_id' => auth()->id(),
                        'borrow_date' => $borrowDate,
                        'start_time' => $payload['start_time'],
                        'end_time' => $payload['end_time'],
                        'event' => $payload['event'],
                        'notes' => $payload['notes'] ?? null,
                        'status' => 'menunggu',
                        'is_recurring' => false,
                        'borrow_code' => $borrowCode, // Set langsung saat pembuatan
                    ]);

                    Log::info('LabBorrowingController::store - Created new borrowing', [
                        'id' => $borrowing->id,
                        'borrow_code' => $borrowCode,
                        'user_id' => auth()->id(),
                        'borrow_date' => $borrowDate
                    ]);

                    // Catat history
                    $this->logBorrowingHistory(
                        $borrowing,
                        'menunggu',
                        'Pengajuan peminjaman lab'
                    );

                    return response()->json([
                        'message' => "Berhasil mengajukan peminjaman",
                    ], 200);
                });
            }

            // Proses untuk peminjaman berulang
            Log::debug('LabBorrowingController::store - Processing recurring borrowing', [
                'borrow_date' => $borrowDate,
                'recurrence_type' => $request->input('recurrence_type'),
                'recurrence_interval' => $request->input('recurrence_interval'),
                'ends_option' => $request->input('ends_option')
            ]);

            return DB::transaction(function () use ($request, $payload, $borrowDate, $isRecurring) {
                // Hitung maksimum 1 tahun dari sekarang atau maksimal 52 instance
                $maxDate = Carbon::now()->addYear();
                $maxInstances = 52;

                // Set tanggal akhir berdasarkan opsi yang dipilih
                $endsOption = $request->input('ends_option');
                $endDate = null;
                $recurrenceCount = null;

                if ($endsOption === 'after') {
                    $recurrenceCount = $request->input('recurrence_count');
                    Log::debug('LabBorrowingController::store - Recurring ends after count', ['count' => $recurrenceCount]);
                } elseif ($endsOption === 'on') {
                    $endDate = Carbon::parse($request->input('recurrence_ends_at'));
                    // Pastikan tidak melebihi batas maksimum
                    if ($endDate->gt($maxDate)) {
                        $endDate = $maxDate;
                        Log::debug('LabBorrowingController::store - Adjusted end date to max allowed', ['end_date' => $endDate->format('Y-m-d')]);
                    }
                } else {
                    // Default: 6 bulan dari sekarang jika "never"
                    $endDate = Carbon::now()->addMonths(6);
                    Log::debug('LabBorrowingController::store - Using default end date (6 months)', ['end_date' => $endDate->format('Y-m-d')]);
                }

                // Buat peminjaman utama (parent)
                $parentBorrowCode = $this->generateBorrowingCode();
                Log::debug('LabBorrowingController::store - Generated parent borrow code', ['borrow_code' => $parentBorrowCode]);

                $parentBooking = LabBorrowing::create([
                    'user_id' => auth()->id(),
                    'borrow_date' => $borrowDate,
                    'start_time' => $payload['start_time'],
                    'end_time' => $payload['end_time'],
                    'event' => $payload['event'],
                    'notes' => $payload['notes'] ?? null,
                    'status' => 'menunggu',
                    'is_recurring' => true,
                    'recurrence_type' => $request->input('recurrence_type'),
                    'recurrence_interval' => (int) $request->input('recurrence_interval'),
                    'recurrence_ends_at' => $endDate,
                    'recurrence_count' => $recurrenceCount,
                    'borrow_code' => $parentBorrowCode, // Set langsung saat pembuatan
                ]);

                Log::info('LabBorrowingController::store - Created parent recurring booking', [
                    'id' => $parentBooking->id,
                    'borrow_code' => $parentBorrowCode,
                    'recurrence_type' => $request->input('recurrence_type'),
                    'recurrence_interval' => (int) $request->input('recurrence_interval')
                ]);

                // Catat history
                $this->logBorrowingHistory(
                    $parentBooking,
                    'menunggu',
                    'Pengajuan peminjaman berulang'
                );

                // Buat peminjaman child
                $dates = $this->generateRecurringDates(
                    Carbon::parse($borrowDate),
                    $request->input('recurrence_type'),
                    (int) $request->input('recurrence_interval'),
                    $endDate,
                    $recurrenceCount
                );

                // Skip tanggal pertama karena sudah dibuat sebagai parent
                $dates = $dates->slice(1);

                // Batas maksimal instance
                $dates = $dates->take($maxInstances - 1); // -1 karena parent adalah instance pertama
                Log::debug('LabBorrowingController::store - Generated ' . $dates->count() . ' recurring dates');

                $createdInstances = 1; // Parent sudah dihitung sebagai 1
                $conflicts = [];

                foreach ($dates as $date) {
                    // Cek konflik untuk setiap tanggal
                    $formattedDate = $date->format('Y-m-d');
                    if ($this->hasTimeConflict($formattedDate, $payload['start_time'], $payload['end_time'])) {
                        Log::warning('LabBorrowingController::store - Time conflict for recurring instance', [
                            'date' => $formattedDate,
                            'start_time' => $payload['start_time'],
                            'end_time' => $payload['end_time']
                        ]);

                        $conflicts[] = [
                            'date' => $formattedDate,
                            'formatted_date' => $date->locale('id')->isoFormat('dddd, D MMMM YYYY')
                        ];
                        continue;
                    }

                    // Buat child booking
                    $childBooking = LabBorrowing::create([
                        'user_id' => auth()->id(),
                        'borrow_date' => $formattedDate,
                        'start_time' => $payload['start_time'],
                        'end_time' => $payload['end_time'],
                        'event' => $payload['event'],
                        'notes' => $payload['notes'] ?? null,
                        'status' => 'menunggu',
                        'is_recurring' => false, // Child tidak recurring
                        'parent_booking_id' => $parentBooking->id,
                    ]);

                    if (empty($childBooking->borrow_code)) {
                        $childBooking->borrow_code = $this->generateBorrowingCode();
                        $childBooking->save();
                    }

                    Log::debug('LabBorrowingController::store - Created child recurring booking', [
                        'id' => $childBooking->id,
                        'borrow_code' => $childBooking->borrow_code,
                        'parent_id' => $parentBooking->id,
                        'date' => $formattedDate
                    ]);

                    // Catat history
                    $this->logBorrowingHistory(
                        $childBooking,
                        'menunggu',
                        'Bagian dari peminjaman berulang #' . $parentBooking->borrow_code
                    );

                    $createdInstances++;
                }

                // Update count jika ada konflik
                if ($conflicts && $endsOption === 'after') {
                    $parentBooking->recurrence_count = $createdInstances;
                    $parentBooking->save();
                    Log::info('LabBorrowingController::store - Updated parent recurrence count due to conflicts', [
                        'id' => $parentBooking->id,
                        'new_count' => $createdInstances,
                        'conflict_count' => count($conflicts)
                    ]);
                }

                // Pesan sukses dengan info tambahan jika ada konflik
                $message = "Berhasil mengajukan peminjaman berulang";
                if (count($conflicts) > 0) {
                    $message .= ". " . count($conflicts) . " jadwal tidak dapat dibuat karena konflik";
                }

                Log::info('LabBorrowingController::store - Completed recurring booking creation', [
                    'parent_id' => $parentBooking->id,
                    'created_instances' => $createdInstances,
                    'conflicts' => count($conflicts)
                ]);

                return response()->json([
                    'message' => $message,
                    'conflicts' => $conflicts,
                    'created_instances' => $createdInstances,
                    'parent_id' => $parentBooking->id
                ], 200);
            });

        } catch (\Exception $e) {
            Log::error('LabBorrowingController::store - Error creating borrowing', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'borrow_date' => $request->borrow_date ?? null,
                'is_recurring' => $request->has('is_recurring') ? $request->is_recurring : null
            ]);

            report($e); // Log the exception
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a unique borrowing code
     *
     * @return string
     */
    protected function generateBorrowingCode()
    {
        Log::debug('LabBorrowingController::generateBorrowingCode - Generating new borrow code');
        $prefix = 'BRW';
        $date = now()->format('ymd');

        // Cari kode peminjaman terakhir dengan prefix dan tanggal yang sama
        $lastBorrowing = LabBorrowing::where('borrow_code', 'like', "{$prefix}{$date}%")
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastBorrowing) {
            // Ekstrak nomor urut terakhir
            $lastCode = $lastBorrowing->borrow_code;
            $lastNumber = (int) substr($lastCode, -3);
            $nextNumber = $lastNumber + 1;
            Log::debug('LabBorrowingController::generateBorrowingCode - Found last code', [
                'last_code' => $lastCode,
                'next_number' => $nextNumber
            ]);
        } else {
            $nextNumber = 1;
            Log::debug('LabBorrowingController::generateBorrowingCode - No existing code found for today, starting with 1');
        }

        // Format nomor urut dengan padding 3 digit (001, 002, dst)
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Buat kode baru
        $newCode = "{$prefix}{$date}{$formattedNumber}";

        // Pastikan kode benar-benar unik dengan melakukan pengecekan berulang
        while (LabBorrowing::where('borrow_code', $newCode)->exists()) {
            $nextNumber++;
            $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            $newCode = "{$prefix}{$date}{$formattedNumber}";
            Log::debug('LabBorrowingController::generateBorrowingCode - Code collision, incrementing', [
                'new_code' => $newCode
            ]);
        }

        Log::debug('LabBorrowingController::generateBorrowingCode - Generated unique code', ['code' => $newCode]);
        return $newCode;
    }

    /**
     * Check if there's a time conflict with existing approved bookings
     */
    private function hasTimeConflict($date, $startTime, $endTime, $excludeId = null)
    {
        Log::debug('LabBorrowingController::hasTimeConflict - Checking time conflict', [
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'exclude_id' => $excludeId
        ]);

        $query = LabBorrowing::where('borrow_date', $date)
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

        $hasConflict = $query->exists();

        if ($hasConflict) {
            Log::warning('LabBorrowingController::hasTimeConflict - Conflict detected', [
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);
        }

        return $hasConflict;
    }

    /**
     * Check if there's a time conflict and return detailed information
     */
    private function getTimeConflictDetails($date, $startTime, $endTime, $excludeId = null)
    {
        Log::debug('LabBorrowingController::getTimeConflictDetails - Checking detailed time conflict', [
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'exclude_id' => $excludeId
        ]);

        $existingBooking = LabBorrowing::where('borrow_date', $date)
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

        if ($excludeId) {
            $existingBooking->where('id', '!=', $excludeId);
        }

        $booking = $existingBooking->first();

        if ($booking) {
            Log::warning('LabBorrowingController::getTimeConflictDetails - Conflict found', [
                'conflict_booking_id' => $booking->id,
                'conflict_user' => $booking->user->name,
                'conflict_time' => Carbon::parse($booking->start_time)->format('H:i') . ' - ' . Carbon::parse($booking->end_time)->format('H:i')
            ]);

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
        Log::info('LabBorrowingController::userBorrowingData - Fetching user borrowing data', [
            'user_id' => auth()->id(),
            'filters' => $request->only(['status', 'start_date', 'end_date'])
        ]);

        $query = LabBorrowing::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->orderBy('borrow_date', 'desc')
            ->orderBy('start_time', 'desc');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->start_date) {
            $query->where('borrow_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('borrow_date', '<=', $request->end_date);
        }

        $data = $query->get();
        Log::debug('LabBorrowingController::userBorrowingData - Retrieved ' . $data->count() . ' records');

        if ($data->isEmpty()) {
            return response()->json([
                'data' => []
            ]);
        }

        $counter = 0;

        $transformedData = $data->map(function ($b) use (&$counter) {
            return [
                'id' => $b->id,
                'borrow_date' => DateHelper::formatLong($b->borrow_date),
                'start_time' => $b->start_time,
                'end_time' => $b->end_time,
                'event' => $b->event,
                'status' => $b->status,
                'number' => ++$counter,
                'borrow_code' => $b->borrow_code
            ];
        });

        return response()->json([
            'data' => $transformedData,
        ]);
    }

    public function show(LabBorrowing $borrowing)
    {
        Log::info('LabBorrowingController::show - Viewing borrowing details', [
            'borrowing_id' => $borrowing->id,
            'borrow_code' => $borrowing->borrow_code,
            'user_id' => auth()->id(),
            'method' => __METHOD__,
            'file' => __FILE__
        ]);

        $timelineHtml = view('pages.borrowing.lab.requests._timeline', [
            'borrow' => $borrowing
        ])->render();

        // Tambahkan info perulangan jika booking berulang
        $recurrenceInfo = null;
        if ($borrowing->is_recurring) {
            $childCount = $borrowing->childBookings()->count();
            $totalInstances = $childCount + 1; // +1 untuk parent booking

            Log::debug('LabBorrowingController::show - Processing recurring booking', [
                'borrowing_id' => $borrowing->id,
                'is_recurring' => true,
                'child_count' => $childCount,
                'total_instances' => $totalInstances
            ]);

            $recurrenceInfo = [
                'type' => $borrowing->recurrence_type,
                'interval' => $borrowing->recurrence_interval,
                'label' => $borrowing->recurrence_label,
                'total_instances' => $totalInstances,
                'ends_at' => $borrowing->recurrence_ends_at ? Carbon::parse($borrowing->recurrence_ends_at)->locale('id')->isoFormat('D MMMM Y') : null
            ];
        } elseif ($borrowing->parent_booking_id) {
            // Ini adalah child booking
            $parentBooking = $borrowing->parentBooking;
            if ($parentBooking) {
                $childCount = $parentBooking->childBookings()->count();
                $totalInstances = $childCount + 1;

                Log::debug('LabBorrowingController::show - Processing child booking', [
                    'borrowing_id' => $borrowing->id,
                    'parent_id' => $parentBooking->id,
                    'parent_code' => $parentBooking->borrow_code,
                    'child_count' => $childCount,
                    'total_instances' => $totalInstances
                ]);

                $recurrenceInfo = [
                    'is_child' => true,
                    'parent_id' => $parentBooking->id,
                    'parent_code' => $parentBooking->borrow_code,
                    'parent_date' => Carbon::parse($parentBooking->borrow_date)->locale('id')->isoFormat('D MMMM Y'),
                    'instance_number' => $parentBooking->childBookings()->where('borrow_date', '<=', $borrowing->borrow_date)->count() + 1,
                    'total_instances' => $totalInstances
                ];
            }
        }

        $data = [
            'id' => $borrowing->id,
            'borrow_date' => Carbon::parse($borrowing->borrow_date)->locale('id')->isoFormat('D MMMM Y'),
            'start_time' => $borrowing->start_time,
            'end_time' => $borrowing->end_time,
            'event' => $borrowing->event,
            'status' => $borrowing->status,
            'notes' => $borrowing->notes,
            'created_at' => $borrowing->created_at->locale('id')->isoFormat('D MMMM Y HH:mm'),
            'borrower' => $borrowing->user->name ?? 'tidak terdefinisi',
            'borrow_code' => $borrowing->borrow_code,
            'recurrence' => $recurrenceInfo
        ];

        Log::debug('LabBorrowingController::show - Response data prepared', [
            'borrowing_id' => $borrowing->id,
            'has_recurrence_info' => !is_null($recurrenceInfo)
        ]);

        return response()->json([
            'data' => $data,
            'timelineHtml' => $timelineHtml
        ]);
    }

    /**
     * Batalkan peminjaman
     */
    public function cancel(Request $request, LabBorrowing $borrowing)
    {
        Log::info('LabBorrowingController::cancel - Cancellation request received', [
            'borrowing_id' => $borrowing->id,
            'borrow_code' => $borrowing->borrow_code,
            'user_id' => auth()->id(),
            'method' => __METHOD__,
            'file' => __FILE__
        ]);

        // Validasi alasan pembatalan
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // Verifikasi status sebelum dibatalkan
        if (!in_array($borrowing->status, ['menunggu', 'disetujui'])) {
            Log::warning('LabBorrowingController::cancel - Invalid status for cancellation', [
                'borrowing_id' => $borrowing->id,
                'current_status' => $borrowing->status,
                'allowed_statuses' => ['menunggu', 'disetujui']
            ]);

            return response()->json([
                'message' => 'Peminjaman tidak dapat dibatalkan karena status saat ini: ' . $borrowing->status
            ], 422);
        }

        // Verifikasi akses
        if (auth()->id() != $borrowing->user_id && !auth()->user()->hasRole('admin')) {
            Log::warning('LabBorrowingController::cancel - Unauthorized cancellation attempt', [
                'borrowing_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'owner_id' => $borrowing->user_id
            ]);

            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk membatalkan peminjaman ini'
            ], 403);
        }

        // Tambahkan pengecekan apakah ini peminjaman berulang atau bagian dari peminjaman berulang
        $isParentBooking = $borrowing->is_recurring;
        $isChildBooking = !empty($borrowing->parent_booking_id);

        Log::debug('LabBorrowingController::cancel - Booking type check', [
            'borrowing_id' => $borrowing->id,
            'is_parent_booking' => $isParentBooking,
            'is_child_booking' => $isChildBooking
        ]);

        // Jika ini peminjaman berulang, tanyakan konfirmasi melalui request parameter
        if ($isParentBooking && !$request->has('cancel_all_confirmed')) {
            Log::info('LabBorrowingController::cancel - Recurring booking cancellation needs confirmation', [
                'borrowing_id' => $borrowing->id
            ]);

            return response()->json([
                'message' => 'Ini adalah peminjaman berulang. Silakan konfirmasi apakah Anda ingin membatalkan semua jadwal terkait.',
                'is_recurring' => true,
                'id' => $borrowing->id
            ], 200);
        }

        try {
            return DB::transaction(function () use ($borrowing, $request, $isParentBooking, $isChildBooking) {
                $reason = $request->reason;

                Log::info('LabBorrowingController::cancel - Processing cancellation in transaction', [
                    'borrowing_id' => $borrowing->id,
                    'reason' => $reason
                ]);

                // Batalkan peminjaman
                $borrowing->status = 'dibatalkan';
                $borrowing->save();

                // Catat history
                $this->logBorrowingHistory(
                    $borrowing,
                    'dibatalkan',
                    'Dibatalkan oleh ' . auth()->user()->name . ': ' . $reason
                );

                // Jika user bukan pemilik peminjaman (berarti admin), kirim notifikasi ke user
                if (auth()->id() != $borrowing->user_id) {
                    Log::debug('LabBorrowingController::cancel - Sending notification to borrowing owner', [
                        'borrowing_id' => $borrowing->id,
                        'owner_id' => $borrowing->user_id
                    ]);

                    // Kirim notifikasi ke user
                    $borrowing->user->notify(new BorrowingCanceled($borrowing));
                }

                $message = "Berhasil membatalkan peminjaman";

                // Jika ini bagian dari peminjaman berulang, tambahkan info
                if ($isChildBooking) {
                    $message = "Berhasil membatalkan jadwal peminjaman";
                }

                Log::info('LabBorrowingController::cancel - Cancellation completed successfully', [
                    'borrowing_id' => $borrowing->id,
                    'new_status' => 'dibatalkan',
                    'is_child_booking' => $isChildBooking
                ]);

                return response()->json([
                    'message' => $message,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('LabBorrowingController::cancel - Error during cancellation', [
                'borrowing_id' => $borrowing->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            report($e);
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
        Log::info('LabBorrowingController::print - Generating PDF for borrowing', [
            'borrowing_id' => $borrowing->id,
            'borrow_code' => $borrowing->borrow_code,
            'user_id' => auth()->id(),
            'method' => __METHOD__,
            'file' => __FILE__
        ]);

        // Prepare logo as base64
        $logoPath = public_path(settings('logo'));
        $logoBase64 = $this->prepareImageBase64($logoPath);

        // Prepare watermark as base64 (using the same logo but with low opacity)
        $watermarkBase64 = $this->prepareImageBase64($logoPath, 0.1);

        Log::debug('LabBorrowingController::print - Images prepared for PDF', [
            'logo_path' => $logoPath,
            'logo_exists' => file_exists($logoPath),
            'logo_base64_length' => $logoBase64 ? strlen($logoBase64) : 0
        ]);

        $pdf = PDF::loadView('pages.borrowing.lab.print', compact('borrowing', 'logoBase64', 'watermarkBase64'));

        Log::info('LabBorrowingController::print - PDF generated successfully', [
            'borrowing_id' => $borrowing->id
        ]);

        return $pdf->stream('peminjaman-lab.pdf');
    }

    /**
     * Prepare image as base64 string
     */
    private function prepareImageBase64($imagePath, $opacity = 1.0)
    {
        if (!file_exists($imagePath)) {
            Log::warning('LabBorrowingController::prepareImageBase64 - Image file not found', [
                'image_path' => $imagePath,
                'method' => __METHOD__
            ]);
            return null;
        }

        // For watermark (if opacity < 1), adjust the image
        if ($opacity < 1.0 && function_exists('imagecreatefromstring')) {
            Log::debug('LabBorrowingController::prepareImageBase64 - Creating watermark with reduced opacity', [
                'opacity' => $opacity,
                'image_path' => $imagePath
            ]);

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

        Log::debug('LabBorrowingController::prepareImageBase64 - Regular image processed', [
            'image_path' => $imagePath,
            'image_type' => $type,
            'data_size' => strlen($data)
        ]);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    public function filter(Request $request)
    {
        Log::info('LabBorrowingController::filter - Filtering borrowings', [
            'user_id' => auth()->id(),
            'filters' => $request->only(['status', 'start_date', 'end_date']),
            'method' => __METHOD__,
            'file' => __FILE__
        ]);

        $query = LabBorrowing::where('user_id', auth()->id());

        // Filter berdasarkan status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
            Log::debug('LabBorrowingController::filter - Applying status filter', [
                'status' => $request->status
            ]);
        }

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('borrow_date', '>=', $request->start_date);
            Log::debug('LabBorrowingController::filter - Applying start date filter', [
                'start_date' => $request->start_date
            ]);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('borrow_date', '<=', $request->end_date);
            Log::debug('LabBorrowingController::filter - Applying end date filter', [
                'end_date' => $request->end_date
            ]);
        }

        $data = $query->orderBy('borrow_date', 'desc')->get();

        Log::debug('LabBorrowingController::filter - Query results', [
            'record_count' => $data->count()
        ]);

        $transformedData = $data->map(function ($b) {
            return [
                'id' => $b->id,
                'borrow_date' => Carbon::parse($b->borrow_date)->format('F j, Y'),
                'start_time' => $b->start_time,
                'end_time' => $b->end_time,
                'event' => $b->event,
                'status' => $b->status,
                'number' => 0, // Will be fixed on frontend
                'borrow_code' => $b->borrow_code
            ];
        });

        return response()->json([
            'data' => $transformedData,
        ]);
    }

    /**
     * Menampilkan daftar peminjaman yang menunggu persetujuan
     */
    public function pendingApprovals(Request $request)
    {
        Log::info('LabBorrowingController::pendingApprovals - Viewing pending approvals page', [
            'user_id' => auth()->id(),
            'method' => __METHOD__,
            'file' => __FILE__
        ]);

        $pendingCount = LabBorrowing::where('status', 'menunggu')->count();

        Log::debug('LabBorrowingController::pendingApprovals - Pending count', [
            'pending_count' => $pendingCount
        ]);

        return view('pages.borrowing.lab.management.pending', compact('pendingCount'));
    }

    /**
     * Mendapatkan data peminjaman yang menunggu persetujuan (untuk AJAX)
     */
    public function pendingApprovalsData(Request $request)
    {
        Log::info('LabBorrowingController::pendingApprovalsData - Fetching pending approvals data', [
            'user_id' => auth()->id(),
            'method' => __METHOD__,
            'file' => __FILE__
        ]);

        $data = LabBorrowing::with(['user'])
            ->where('status', 'menunggu')
            ->orderBy('borrow_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        Log::debug('LabBorrowingController::pendingApprovalsData - Query results', [
            'record_count' => $data->count()
        ]);

        $counter = 0;
        $transformedData = $data->map(function ($b) use (&$counter) {
            return [
                'id' => $b->id,
                'peminjam' => $b->user->name,
                'borrow_date' => DateHelper::formatLong($b->borrow_date),
                'start_time' => $b->start_time,
                'end_time' => $b->end_time,
                'event' => $b->event,
                'status' => $b->status,
                'notes' => $b->notes,
                'created_at' => $b->created_at->diffForHumans(),
                'number' => ++$counter,
                'borrow_code' => $b->borrow_code
            ];
        });

        return response()->json([
            'data' => $transformedData,
        ]);
    }

    /**
     * Menyetujui peminjaman
     */
    public function approve(LabBorrowing $borrowing, Request $request)
    {
        Log::info('LabBorrowingController::approve - Processing approval request', [
            'borrowing_id' => $borrowing->id,
            'borrow_code' => $borrowing->borrow_code,
            'user_id' => auth()->id(),
            'method' => __METHOD__,
            'file' => __FILE__
        ]);

        try {
            // Verifikasi apakah peminjaman masih berstatus menunggu
            if ($borrowing->status !== 'menunggu') {
                Log::warning('LabBorrowingController::approve - Invalid status for approval', [
                    'borrowing_id' => $borrowing->id,
                    'current_status' => $borrowing->status,
                    'expected_status' => 'menunggu'
                ]);

                return response()->json([
                    'message' => 'Peminjaman sudah tidak dalam status menunggu persetujuan'
                ], 422);
            }

            // Periksa konflik waktu
            $borrowDate = $borrowing->borrow_date;
            $startTime = $borrowing->start_time;
            $endTime = $borrowing->end_time;

            if ($this->hasTimeConflict($borrowDate, $startTime, $endTime, $borrowing->id)) {
                Log::warning('LabBorrowingController::approve - Time conflict detected', [
                    'borrowing_id' => $borrowing->id,
                    'borrow_date' => $borrowDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime
                ]);

                return response()->json([
                    'message' => 'Terdapat konflik waktu dengan peminjaman lain yang sudah disetujui'
                ], 422);
            }

            // Gunakan transaction untuk memastikan integritas data
            DB::beginTransaction();

            // Ambil catatan jika ada
            $notes = trim($request->input('notes', ''));

            Log::debug('LabBorrowingController::approve - Updating borrowing status', [
                'borrowing_id' => $borrowing->id,
                'old_status' => $borrowing->status,
                'new_status' => 'disetujui',
                'has_notes' => !empty($notes)
            ]);

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

            DB::commit();

            Log::info('LabBorrowingController::approve - Sending notification to user', [
                'borrowing_id' => $borrowing->id,
                'user_id' => $borrowing->user_id
            ]);

            $borrowing->user->notify(new BorrowingStatusChanged($borrowing, $borrowing->status));

            Log::info('LabBorrowingController::approve - Approval completed successfully', [
                'borrowing_id' => $borrowing->id
            ]);

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

            Log::error('LabBorrowingController::approve - Error during approval', [
                'borrowing_id' => $borrowing->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            report($e);

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menolak peminjaman dengan alasan
     */
    public function reject(LabBorrowing $borrowing, Request $request)
    {
        Log::info('LabBorrowingController::reject - Processing rejection request', [
            'borrowing_id' => $borrowing->id,
            'borrow_code' => $borrowing->borrow_code,
            'user_id' => auth()->id(),
            'method' => __METHOD__,
            'file' => __FILE__
        ]);

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
                Log::warning('LabBorrowingController::reject - Invalid status for rejection', [
                    'borrowing_id' => $borrowing->id,
                    'current_status' => $borrowing->status,
                    'expected_status' => 'menunggu'
                ]);

                return response()->json([
                    'message' => 'Peminjaman sudah tidak dalam status menunggu persetujuan'
                ], 422);
            }

            // Gunakan transaction untuk memastikan integritas data
            DB::beginTransaction();

            // Ambil alasan penolakan dari request
            $rejectionReason = $request->input('notes');

            Log::debug('LabBorrowingController::reject - Updating borrowing status', [
                'borrowing_id' => $borrowing->id,
                'old_status' => $borrowing->status,
                'new_status' => 'ditolak',
                'rejection_reason' => $rejectionReason
            ]);

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

            DB::commit();

            Log::info('LabBorrowingController::reject - Sending notification to user', [
                'borrowing_id' => $borrowing->id,
                'user_id' => $borrowing->user_id
            ]);

            $borrowing->user->notify(new BorrowingStatusChanged($borrowing, $borrowing->status));

            Log::info('LabBorrowingController::reject - Rejection completed successfully', [
                'borrowing_id' => $borrowing->id
            ]);

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

            Log::error('LabBorrowingController::reject - Error during rejection', [
                'borrowing_id' => $borrowing->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            report($e);

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menandai peminjaman sebagai selesai
     */
    public function complete(LabBorrowing $borrowing, Request $request)
    {
        Log::info('LabBorrowingController::complete - Processing completion request', [
            'borrowing_id' => $borrowing->id,
            'borrow_code' => $borrowing->borrow_code,
            'user_id' => auth()->id(),
            'method' => __METHOD__,
            'file' => __FILE__
        ]);

        try {
            // Verifikasi apakah peminjaman berstatus disetujui
            if ($borrowing->status !== 'disetujui' && $borrowing->status !== 'digunakan') {
                Log::warning('LabBorrowingController::complete - Invalid status for completion', [
                    'borrowing_id' => $borrowing->id,
                    'current_status' => $borrowing->status,
                    'allowed_statuses' => ['disetujui', 'digunakan']
                ]);

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

            Log::debug('LabBorrowingController::complete - Updating borrowing status', [
                'borrowing_id' => $borrowing->id,
                'old_status' => $borrowing->status,
                'new_status' => 'selesai',
                'completion_notes' => $completionNotes
            ]);

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

            DB::commit();

            Log::info('LabBorrowingController::complete - Sending notification to user', [
                'borrowing_id' => $borrowing->id,
                'user_id' => $borrowing->user_id
            ]);

            $borrowing->user->notify(new BorrowingStatusChanged($borrowing, $borrowing->status));

            Log::info('LabBorrowingController::complete - Completion process finished successfully', [
                'borrowing_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'timestamp' => now()->toIso8601String(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return response()->json([
                'message' => 'Peminjaman berhasil diselesaikan',
                'data' => [
                    'id' => $borrowing->id,
                    'status' => 'selesai',
                    'completedBy' => auth()->user()->name,
                    'updated_at' => $borrowing->updated_at->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('LabBorrowingController::complete - Error during completion process', [
                'borrowing_id' => $borrowing->id ?? null,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id() ?? 'unauthenticated'
            ]);

            report($e);

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan halaman riwayat aktivitas peminjaman
     */
    public function showHistory(LabBorrowing $borrowing)
    {
        // Verifikasi akses - hanya admin atau pemilik
        if (!auth()->user()->hasRole(['admin', 'superadmin']) && $borrowing->user_id !== auth()->id()) {
            Log::warning('LabBorrowingController::showHistory - Unauthorized access attempt', [
                'borrowing_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'user_roles' => auth()->user()->getRoleNames(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            abort(403, 'Anda tidak memiliki akses ke data ini');
        }

        Log::debug('LabBorrowingController::showHistory - Fetching history', [
            'borrowing_id' => $borrowing->id,
            'user_id' => auth()->id(),
            'method' => __METHOD__
        ]);

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

        Log::debug('LabBorrowingController::showHistory - History fetched successfully', [
            'borrowing_id' => $borrowing->id,
            'history_count' => $histories->count(),
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        return view('pages.borrowing.lab.requests.history', [
            'borrowing' => $borrowing,
            'histories' => $histories
        ]);
    }

    /**
     * Menampilkan form untuk mengajukan ulang peminjaman yang ditolak
     */
    public function resubmit(Request $request)
    {
        // Validasi referensi
        $referenceId = $request->query('reference');
        if (!$referenceId) {
            Log::warning('LabBorrowingController::resubmit - Missing reference ID', [
                'user_id' => auth()->id(),
                'request_params' => $request->all(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return redirect()->route('borrowing.lab.list')
                ->with('error', 'Referensi peminjaman tidak ditemukan');
        }

        // Ambil data peminjaman sebelumnya
        $reference = LabBorrowing::where('id', $referenceId)
            ->where('user_id', auth()->id())
            ->where('status', 'ditolak') // Pastikan hanya yang ditolak yang bisa diajukan ulang
            ->first();

        if (!$reference) {
            Log::warning('LabBorrowingController::resubmit - Invalid reference', [
                'reference_id' => $referenceId,
                'user_id' => auth()->id(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return redirect()->route('borrowing.lab.list')
                ->with('error', 'Peminjaman yang akan diajukan ulang tidak ditemukan atau tidak dapat diajukan ulang');
        }

        Log::debug('LabBorrowingController::resubmit - Preparing resubmission form', [
            'reference_id' => $reference->id,
            'user_id' => auth()->id(),
            'method' => __METHOD__
        ]);

        // Siapkan data untuk form
        $previousData = [
            'borrow_date' => $reference->borrow_date,
            'start_time' => $reference->start_time,
            'end_time' => $reference->end_time,
            'event' => $reference->event,
            'notes' => $reference->notes,
            'rejection_reason' => $reference->notes, // Alasan penolakan
            'reference_id' => $reference->id,
        ];

        return view('pages.borrowing.lab.requests.resubmit', compact('previousData'));
    }

    /**
     * Menyimpan pengajuan ulang peminjaman
     */
    public function resubmitStore(Request $request)
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

        Log::debug('LabBorrowingController::resubmitStore - Validation passed', [
            'reference_id' => $validatedData['reference_id'],
            'user_id' => auth()->id(),
            'borrow_date' => $validatedData['borrow_date'],
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        // Ambil referensi
        $reference = LabBorrowing::where('id', $validatedData['reference_id'])
            ->where('user_id', auth()->id())
            ->where('status', 'ditolak')
            ->first();

        if (!$reference) {
            Log::warning('LabBorrowingController::resubmitStore - Invalid reference', [
                'reference_id' => $validatedData['reference_id'],
                'user_id' => auth()->id(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return response()->json([
                'message' => 'Referensi peminjaman tidak valid'
            ], 422);
        }

        // Periksa konflik waktu
        $borrowDate = Carbon::parse($validatedData['borrow_date'])->format('Y-m-d');
        if ($this->hasTimeConflict($borrowDate, $validatedData['start_time'], $validatedData['end_time'])) {
            Log::info('LabBorrowingController::resubmitStore - Time conflict detected', [
                'borrow_date' => $borrowDate,
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'user_id' => auth()->id(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return response()->json([
                'message' => 'Waktu yang dipilih sudah dipesan. Silahkan pilih waktu lain.'
            ], 422);
        }

        try {
            // Gunakan transaction
            DB::beginTransaction();

            Log::debug('LabBorrowingController::resubmitStore - Creating new borrowing record', [
                'reference_id' => $reference->id,
                'user_id' => auth()->id(),
                'method' => __METHOD__
            ]);

            // Buat peminjaman baru
            $newBorrowing = LabBorrowing::create([
                'user_id' => auth()->id(),
                'borrow_date' => $borrowDate,
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'event' => $validatedData['event'],
                'notes' => $validatedData['notes'] ?? null,
                'status' => 'menunggu'
            ]);

            // Generate kode peminjaman
            $newBorrowing->borrow_code = $this->generateBorrowingCode();
            $newBorrowing->save();

            // Catat riwayat
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

            DB::commit();

            Log::info('LabBorrowingController::resubmitStore - Resubmission completed successfully', [
                'new_borrowing_id' => $newBorrowing->id,
                'reference_id' => $reference->id,
                'user_id' => auth()->id(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return response()->json([
                'message' => 'Pengajuan ulang peminjaman berhasil dikirim',
                'data' => [
                    'id' => $newBorrowing->id
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('LabBorrowingController::resubmitStore - Error during resubmission', [
                'reference_id' => $reference->id ?? null,
                'user_id' => auth()->id(),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check availability without creating a booking
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'borrow_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'exclude_id' => 'nullable|exists:lab_borrowings,id',
        ]);

        Log::debug('LabBorrowingController::checkAvailability - Checking time availability', [
            'borrow_date' => $request->borrow_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'exclude_id' => $request->exclude_id,
            'user_id' => auth()->id(),
            'method' => __METHOD__
        ]);

        $conflict = $this->getTimeConflictDetails(
            Carbon::parse($request->borrow_date)->format('Y-m-d'),
            $request->start_time,
            $request->end_time,
            $request->exclude_id
        );

        if ($conflict['has_conflict']) {
            Log::info('LabBorrowingController::checkAvailability - Conflict detected', [
                'conflict_with' => $conflict['conflict_with'],
                'borrow_date' => $request->borrow_date,
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return response()->json([
                'available' => false,
                'conflict' => $conflict['conflict_with']
            ]);
        }

        return response()->json(['available' => true]);
    }

    /**
     * Generate tanggal-tanggal untuk peminjaman berulang
     *
     * @param Carbon $startDate
     * @param string $type
     * @param int $interval
     * @param Carbon|null $endDate
     * @param int|null $count
     * @return \Illuminate\Support\Collection
     */
    private function generateRecurringDates($startDate, $type, $interval, $endDate = null, $count = null)
    {
        $dates = collect([$startDate]);
        $currentDate = $startDate->copy();
        $iterations = 0;

        Log::debug('LabBorrowingController::generateRecurringDates - Generating dates', [
            'start_date' => $startDate->toDateString(),
            'type' => $type,
            'interval' => $interval,
            'end_date' => $endDate ? $endDate->toDateString() : null,
            'count' => $count,
            'method' => __METHOD__
        ]);

        while (true) {
            // Tambahkan interval sesuai jenis
            switch ($type) {
                case 'daily':
                    $currentDate = $currentDate->copy()->addDays($interval);
                    break;
                case 'weekly':
                    $currentDate = $currentDate->copy()->addWeeks($interval);
                    break;
                case 'monthly':
                    $currentDate = $currentDate->copy()->addMonths($interval);
                    break;
            }

            $iterations++;

            // Cek apakah sudah mencapai batas
            if ($count && $iterations >= $count) {
                break;
            }

            if ($endDate && $currentDate->gt($endDate)) {
                break;
            }

            // Maksimum 1 tahun ke depan
            if ($currentDate->gt(Carbon::now()->addYear())) {
                break;
            }

            $dates->push($currentDate->copy());
        }

        Log::debug('LabBorrowingController::generateRecurringDates - Generated dates', [
            'date_count' => $dates->count(),
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        return $dates;
    }

    /**
     * Get all recurring instances for a parent booking
     */
    public function getRecurringInstances(LabBorrowing $borrowing, Request $request)
    {
        // Check if this is a parent booking
        if (!$borrowing->is_recurring) {
            Log::warning('LabBorrowingController::getRecurringInstances - Not a recurring booking', [
                'borrowing_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return response()->json([
                'message' => 'This is not a recurring booking'
            ], 422);
        }

        Log::debug('LabBorrowingController::getRecurringInstances - Fetching instances', [
            'parent_id' => $borrowing->id,
            'user_id' => auth()->id(),
            'method' => __METHOD__
        ]);

        // Get all instances (parent + children)
        $instances = collect([$borrowing]);
        $childBookings = $borrowing->childBookings()->orderBy('borrow_date')->get();
        $instances = $instances->concat($childBookings);

        $formattedInstances = $instances->map(function ($instance, $index) {
            return [
                'id' => $instance->id,
                'instance_number' => $index + 1,
                'borrow_date' => Carbon::parse($instance->borrow_date)->locale('id')->isoFormat('dddd, D MMMM Y'),
                'time' => Carbon::parse($instance->start_time)->format('H:i') . ' - ' . Carbon::parse($instance->end_time)->format('H:i'),
                'status' => $instance->status,
                'status_badge' => $this->getStatusBadge($instance->status),
                'is_parent' => $instance->id === $instance->parent_booking_id,
                'borrow_code' => $instance->borrow_code
            ];
        });

        Log::debug('LabBorrowingController::getRecurringInstances - Instances fetched', [
            'parent_id' => $borrowing->id,
            'instance_count' => $instances->count(),
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        return response()->json([
            'instances' => $formattedInstances,
            'parent' => [
                'id' => $borrowing->id,
                'event' => $borrowing->event,
                'recurrence_label' => $borrowing->recurrence_label
            ]
        ]);
    }

    /**
     * Helper untuk mendapatkan badge status
     */
    private function getStatusBadge($status)
    {
        $badge = [
            'class' => 'bg-secondary',
            'icon' => 'fa-question-circle',
            'label' => ucfirst($status)
        ];

        switch ($status) {
            case 'menunggu':
                $badge = [
                    'class' => 'bg-warning',
                    'icon' => 'fa-clock',
                    'label' => 'Menunggu'
                ];
                break;
            case 'disetujui':
                $badge = [
                    'class' => 'bg-success',
                    'icon' => 'fa-check-circle',
                    'label' => 'Disetujui'
                ];
                break;
            case 'ditolak':
                $badge = [
                    'class' => 'bg-danger',
                    'icon' => 'fa-times-circle',
                    'label' => 'Ditolak'
                ];
                break;
            case 'selesai':
                $badge = [
                    'class' => 'bg-secondary',
                    'icon' => 'fa-check-double',
                    'label' => 'Selesai'
                ];
                break;
            case 'dibatalkan':
                $badge = [
                    'class' => 'bg-danger bg-opacity-50',
                    'icon' => 'fa-ban',
                    'label' => 'Dibatalkan'
                ];
                break;
            case 'kadaluarsa':
                $badge = [
                    'class' => 'bg-dark',
                    'icon' => 'fa-calendar-times',
                    'label' => 'Kadaluarsa'
                ];
                break;
        }

        return $badge;
    }

    /**
     * Cancel all recurring instances
     */
    public function cancelAllRecurring(LabBorrowing $borrowing, Request $request)
    {
        // Verifikasi bahwa ini adalah peminjaman berulang
        if (!$borrowing->is_recurring) {
            Log::warning('LabBorrowingController::cancelAllRecurring - Not a recurring booking', [
                'borrowing_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return response()->json([
                'message' => 'Ini bukan peminjaman berulang'
            ], 422);
        }

        // Verifikasi kepemilikan / hak akses
        if ($borrowing->user_id !== auth()->id() && !auth()->user()->hasRole(['admin', 'superadmin'])) {
            Log::warning('LabBorrowingController::cancelAllRecurring - Unauthorized access attempt', [
                'borrowing_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'user_roles' => auth()->user()->getRoleNames(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk membatalkan peminjaman ini'
            ], 403);
        }

        try {
            DB::beginTransaction();

            Log::info('LabBorrowingController::cancelAllRecurring - Starting cancellation process', [
                'parent_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'method' => __METHOD__
            ]);

            // Ambil alasan pembatalan
            $cancellationReason = trim($request->input('notes', ''));
            $notes = !empty($cancellationReason)
                ? $cancellationReason
                : 'Seluruh jadwal berulang dibatalkan oleh ' . auth()->user()->name . ' pada ' . now()->format('d/m/Y H:i');

            // Batalkan peminjaman utama
            $parentCanBeCancelled = in_array($borrowing->status, ['menunggu', 'disetujui']);

            if ($parentCanBeCancelled) {
                // Jika disetujui, cek apakah sudah dimulai
                if ($borrowing->status === 'disetujui') {
                    $now = Carbon::now();
                    $borrowDateTime = Carbon::parse($borrowing->borrow_date . ' ' . $borrowing->start_time);

                    if ($borrowDateTime->lte($now)) {
                        $parentCanBeCancelled = false;

                        Log::debug('LabBorrowingController::cancelAllRecurring - Parent booking already started, cannot cancel', [
                            'parent_id' => $borrowing->id,
                            'borrow_date' => $borrowing->borrow_date,
                            'start_time' => $borrowing->start_time,
                            'file' => __FILE__,
                            'line' => __LINE__
                        ]);
                    }
                }

                if ($parentCanBeCancelled) {
                    $borrowing->status = 'dibatalkan';
                    $borrowing->notes = $notes;
                    $borrowing->save();

                    // Catat riwayat pembatalan
                    $this->logBorrowingHistory(
                        $borrowing,
                        'dibatalkan',
                        'Dibatalkan sebagai bagian dari pembatalan seluruh jadwal berulang'
                    );

                    Log::debug('LabBorrowingController::cancelAllRecurring - Parent booking cancelled', [
                        'parent_id' => $borrowing->id,
                        'file' => __FILE__,
                        'line' => __LINE__
                    ]);
                }
            }

            // Batalkan semua peminjaman anak (children)
            $childrenToCancel = $borrowing->childBookings()
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->get();

            $cancelledCount = 0;
            $skippedCount = 0;

            foreach ($childrenToCancel as $child) {
                $canBeCancelled = true;

                // Jika disetujui, cek apakah sudah dimulai
                if ($child->status === 'disetujui') {
                    $now = Carbon::now();
                    $childDateTime = Carbon::parse($child->borrow_date . ' ' . $child->start_time);

                    if ($childDateTime->lte($now)) {
                        $canBeCancelled = false;
                        $skippedCount++;

                        Log::debug('LabBorrowingController::cancelAllRecurring - Child booking already started, skipping', [
                            'child_id' => $child->id,
                            'borrow_date' => $child->borrow_date,
                            'start_time' => $child->start_time,
                            'file' => __FILE__,
                            'line' => __LINE__
                        ]);

                        continue;
                    }
                }

                if ($canBeCancelled) {
                    $child->status = 'dibatalkan';
                    $child->notes = $notes;
                    $child->save();

                    // Catat riwayat pembatalan
                    $this->logBorrowingHistory(
                        $child,
                        'dibatalkan',
                        'Dibatalkan sebagai bagian dari pembatalan seluruh jadwal berulang'
                    );

                    $cancelledCount++;

                    Log::debug('LabBorrowingController::cancelAllRecurring - Child booking cancelled', [
                        'child_id' => $child->id,
                        'file' => __FILE__,
                        'line' => __LINE__
                    ]);
                }
            }

            DB::commit();

            // Kirim notifikasi (opsional)
            if ($parentCanBeCancelled) {
                $borrowing->user->notify(new BorrowingStatusChanged($borrowing, 'dibatalkan'));

                Log::debug('LabBorrowingController::cancelAllRecurring - Notification sent', [
                    'parent_id' => $borrowing->id,
                    'user_id' => $borrowing->user_id,
                    'file' => __FILE__,
                    'line' => __LINE__
                ]);
            }

            Log::info('LabBorrowingController::cancelAllRecurring - Cancellation process completed', [
                'parent_id' => $borrowing->id,
                'parent_cancelled' => $parentCanBeCancelled,
                'cancelled_count' => $cancelledCount,
                'skipped_count' => $skippedCount,
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            return response()->json([
                'message' => 'Peminjaman berulang berhasil dibatalkan',
                'data' => [
                    'parent_cancelled' => $parentCanBeCancelled,
                    'cancelled_count' => $cancelledCount,
                    'skipped_count' => $skippedCount
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('LabBorrowingController::cancelAllRecurring - Error during cancellation process', [
                'parent_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
