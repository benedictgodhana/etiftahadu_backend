<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'card_id',
        'ticket_number',
        'amount',
    ];

    // Automatically generate a ticket number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->ticket_number = 'TICKET-' . strtoupper(uniqid());
        });
    }
}
