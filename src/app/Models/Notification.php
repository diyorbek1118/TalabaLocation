<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'type',
        'message',
        'status',
    ];

    // 🔗 Notification kim tomonidan yuborilganini ko‘rsatadi
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
