<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LabBorrowing extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lab_borrowings';
    protected $guarded = ['id'];
    protected $dates = ['borrow_date', 'created_at', 'updated_at'];

    protected $fillable = [
        'user_id',
        'event',
        'borrow_date',
        'start_time',
        'end_time',
        'notes',
        'status',
        'borrow_code',
        'letter_code',
        'is_recurring',
        'recurrence_type',
        'recurrence_interval',
        'recurrence_ends_at',
        'recurrence_count',
        'parent_booking_id'
    ];

    protected $attributes = [
        'status' => 'menunggu' // Default status
    ];

    public function setBorrowDateAttribute($value)
    {
        $this->attributes['borrow_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(LabBorrowingHistory::class, 'borrowing_id')
            ->orderBy('created_at', 'desc');
    }

    public function parentBooking()
    {
        return $this->belongsTo(LabBorrowing::class, 'parent_booking_id');
    }

    public function childBookings()
    {
        return $this->hasMany(LabBorrowing::class, 'parent_booking_id');
    }

    public function getIsRecurringAttribute($value)
    {
        return (bool) $value;
    }

    public function getRecurrenceLabelAttribute()
    {
        if (!$this->is_recurring) {
            return 'Tidak Berulang';
        }

        $interval = $this->recurrence_interval;
        $label = '';

        switch ($this->recurrence_type) {
            case 'daily':
                $label = $interval > 1 ? "Setiap $interval hari" : "Setiap hari";
                break;
            case 'weekly':
                $label = $interval > 1 ? "Setiap $interval minggu" : "Setiap minggu";
                break;
            case 'monthly':
                $label = $interval > 1 ? "Setiap $interval bulan" : "Setiap bulan";
                break;
        }

        return $label;
    }
}
