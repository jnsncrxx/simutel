<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Housekeeping extends Model
{
    use HasFactory;

    //Relationship with Rooms
    public function Rooms(){
        return $this->belongsTo(Rooms::class, 'room_id');
    }
}
