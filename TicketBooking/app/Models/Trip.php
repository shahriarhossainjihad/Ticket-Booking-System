<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_id', 'trip_schedule_id', 'ticket_price'];

    // Relationships
    public function Vehicles()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function tripSchedule()
    {
        return $this->belongsTo(TripSchedule::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
