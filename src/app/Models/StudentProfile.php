<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'faculty',
        'group_name',
        'course',
        'tutor',
        'rent_area',
        'rent_address',
        'rent_map_url',
    ];

    // 🔗 1-1: Student Profile bitta foydalanuvchiga tegishli
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
