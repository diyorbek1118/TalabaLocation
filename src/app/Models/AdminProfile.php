<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model
{
    protected $table ='admin_profile';

    protected $fillable = [
        'user_id',
        'position',
        'province',
        'district'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
