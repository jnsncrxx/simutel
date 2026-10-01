<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room_pictures extends Model
{
    use HasFactory;

     //Relationship with Room_types
     public function Room_types(){
        return $this->belongsTo(Room_types::class, 'room_type_id');
    }
}
