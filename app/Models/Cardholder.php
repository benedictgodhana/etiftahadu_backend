<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cardholder extends Model
{
    use HasFactory, SoftDeletes;

    // The table associated with the model
    protected $table = 'cardholders';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_id',
    ];

    // The attributes that should be hidden for arrays
    protected $hidden = [
        'user_id', // Hide the user_id in API responses if needed
    ];

    // Relationship with the User model (assuming one user can have many cardholders)
    public function user()
    {
        return $this->belongsTo(User::class); // One cardholder belongs to one user
    }

    // Relationship with the Card model (assuming one cardholder can have many cards)
    public function cards()
    {
        return $this->hasMany(Card::class); // One cardholder can have many cards
    }
}
