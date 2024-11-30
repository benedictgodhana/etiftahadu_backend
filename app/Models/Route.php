<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    // Fillable attributes
    protected $fillable = ['from', 'to', 'fare', 'user_id'];

    /**
     * Relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Method to fetch a route between two locations.
     *
     * @param string $from
     * @param string $to
     * @return Route|null
     */
    public static function getRouteWithFare(string $from, string $to)
    {
        return self::where('from', $from)
            ->where('to', $to)
            ->first(); // Fetch the first matching route
    }
}
