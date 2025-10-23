<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 🔗 1-1: Student Profile bilan bog‘lanish
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    // 🔗 1-N: User (renter) bir nechta rent e’loni joylashi mumkin
    public function rents()
    {
        return $this->hasMany(Rent::class, 'renter_id');
    }

    // 🔗 1-N: User ko‘p notification yuborishi mumkin
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'sender_id');
    }
}
