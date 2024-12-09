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


    public function route()
{
    return $this->belongsTo(Route::class);
}


// In TicketTransaction.php model
public function card()
{
    return $this->belongsTo(Card::class);  // Adjust this depending on the actual relationship
}

}
