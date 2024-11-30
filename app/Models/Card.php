<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes

class Card extends Model
{
    use HasFactory, SoftDeletes; // Use SoftDeletes trait


    protected $table = 'nfc_cards';

    protected $fillable = [
        'name', 'email', 'tel', 'status', 'serial_number', 'user_id'
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
