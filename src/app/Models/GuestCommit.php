<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestCommit extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'phone',
        'email',
        'message',
        'status'
    ];
}
