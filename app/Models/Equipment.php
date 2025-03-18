<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Equipment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'code',
        'description',
        'stock',
        'condition',
        'category_id',
        'location_id',
        'file_id'
    ];

    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    public function location()
    {
        return $this->belongsTo(EquipmentLocation::class, 'location_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function borrowings()
    {
        return $this->hasMany(EquipmentBorrowing::class);
    }
}
