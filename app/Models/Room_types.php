<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room_types extends Model
{
    use HasFactory;

    //Relationship with Rooms
    public function Rooms(){
        return $this->hasMany(Rooms::class, 'room_type_id');
    }

    //Relationship with Room_pictures
    public function pictures(){
        return $this->hasMany(Room_pictures::class, 'room_type_id');
    }
}
