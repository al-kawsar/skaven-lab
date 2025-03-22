<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;

class Lab extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;


    protected $guarded = ['id'];

    protected $dates = ['created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) str()->uuid(); // Menghasilkan UUID saat membuat model
        });
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'thumbnail');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'lab_id', 'id');
    }

    // Add new relationship for slider images
    public function sliderImages()
    {
        return $this->hasMany(LabSlider::class, 'lab_id', 'id');
    }
}
