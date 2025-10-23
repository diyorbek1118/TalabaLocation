<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $fillable = [
        'renter_id',
        'title',
        'description',
        'price',
        'location',
        'status',
    ];

    // 🔗 Rent e’loni bitta ijarachiga tegishli
    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }
}
