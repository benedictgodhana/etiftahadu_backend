<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTransaction extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if the table name follows Laravel's conventions)
    protected $table = 'card_transactions';

    // Define the fillable attributes (you should list the columns that you want to be mass-assignable)
    protected $fillable = [
        'card_id',
        'amount',
    ];

    // Define the relationship with the NFC card (inverse of the relationship in NfcCard)
    public function nfcCard()
    {
        return $this->belongsTo(Card::class);
    }
}
