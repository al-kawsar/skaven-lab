<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Guru extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = ['tgl_lahir', 'created_at', 'updated_at'];

    public function setTglLahirAttribute($value)
    {
        $this->attributes['tgl_lahir'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    // public function getTglLahirAttribute($value)
    // {
    //     return Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    // }
}
