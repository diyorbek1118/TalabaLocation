<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentImage extends Model
{
        protected $filliable=[
        'rent_id',
        'image_path'
    ];

    public function rent(){

        return $this->belongsTo(Rent::class);
    }
}
