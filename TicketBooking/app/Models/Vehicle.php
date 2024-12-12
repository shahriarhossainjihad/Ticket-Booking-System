<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'vehicle_type_id', 'capacity'];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
