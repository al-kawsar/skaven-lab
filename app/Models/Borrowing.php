<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $dates = ['borrow_date', 'created_at', 'updated_at'];

    protected $fillable = [
        'user_id',
        'lab_id',
        'event',
        'participant_count',
        'borrow_date',
        'start_time',
        'end_time',
        'notes'
    ];

    public function setBorrowDateAttribute($value)
    {
        $this->attributes['borrow_date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
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
