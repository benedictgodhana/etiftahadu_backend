<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   CommuteSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'route_number',
        'departure_time',
        'arrival_time',
        'status',
        'fare',
    ];

    // Each schedule belongs to a route
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
