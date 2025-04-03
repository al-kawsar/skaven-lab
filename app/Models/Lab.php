<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\File;

class Lab extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'facilities',
        'status',
        'user_id',
        'thumbnail'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = ['created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) str()->uuid(); // Menghasilkan UUID saat membuat model
        });
    }

    /**
     * Relasi ke file (untuk thumbnail)
     * Untuk kompatibilitas dengan view yang memanggil $lab->file
     */
    public function file()
    {
        return $this->belongsTo(File::class, 'thumbnail');
    }

    /**
     * Alias untuk file - untuk kompatibilitas dengan kode baru
     */
    public function thumbnailFile()
    {
        return $this->file();
    }

    /**
     * Relasi ke slider images
     */
    public function sliderImages()
    {
        return $this->hasMany(LabSlider::class, 'lab_id');
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function borrowings()
    {
        return $this->hasMany(LabBorrowing::class, 'lab_id', 'id');
    }
}
