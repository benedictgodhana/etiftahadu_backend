<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'expiry',
        'duration',
        'percentage',

    ];

    /**
     * Get the user that owns the offer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::saving(function ($offer) {
            $offer->expiry = now()->addDays($offer->duration);
        });
    }


    protected $casts = [
        'expiry' => 'datetime', // This will cast the expiry attribute to a Carbon instance
        'created_at' => 'datetime',
    ];
}
