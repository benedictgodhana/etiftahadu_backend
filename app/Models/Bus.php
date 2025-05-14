<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'capacity',
        'route_id',
        'driver_name',
        'conductor_name',
        'status',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
