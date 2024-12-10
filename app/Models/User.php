<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Import Sanctum's HasApiTokens trait

class User extends Authenticatable
{
    use Notifiable, HasApiTokens; // Adding the roles trait here

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'username',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function topups()
    {
        return $this->hasMany(CardTopup::class);
    }

    // In your model (e.g., Post)
public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

}
