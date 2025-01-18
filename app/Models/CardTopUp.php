<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardTopUp extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'card_id',
        'user_id',
        'amount',
        'transaction_reference',
        'status',
        'offer_id', // Add this to the fillable array
    ];

    // Relationship with the NFC card (one card can have many top-ups)
    public function nfcCard()
    {
        return $this->belongsTo(Card::class);
    }

    // Relationship with the User (who initiated the top-up)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Add Offer relationship
    public function offer()
    {
        return $this->belongsTo(Offer::class); // Assuming you have an Offer model
    }

    // Relationship with the Card (same as nfcCard method, redundant here)
    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
