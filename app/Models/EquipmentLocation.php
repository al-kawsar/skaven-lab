<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'building',
        'floor',
        'room'
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'location_id');
    }

    /**
     * Get the full location display (Building - Floor - Room)
     */
    public function getFullLocationAttribute()
    {
        $parts = [];

        if ($this->building) {
            $parts[] = $this->building;
        }

        if ($this->floor) {
            $parts[] = 'Lantai ' . $this->floor;
        }

        if ($this->room) {
            $parts[] = 'Ruang ' . $this->room;
        }

        return !empty($parts) ? implode(' - ', $parts) : $this->name;
    }
}
