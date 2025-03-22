<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabSlider extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) str()->uuid(); // Generate UUID when creating model
        });
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
