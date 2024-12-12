<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['departure_time', 'arrival_time', 'pickup_point', 'drop_point'];

    // Relationships
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
