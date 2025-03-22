<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Borrowing extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lab_borrowings';
    protected $guarded = ['id'];
    protected $dates = ['borrow_date', 'created_at', 'updated_at'];

    protected $fillable = [
        'user_id',
        'lab_id',
        'event',
        'borrow_date',
        'start_time',
        'end_time',
        'notes',
        'status'
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

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
}
