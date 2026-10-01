<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Additional_charges extends Model
{
    use HasFactory;

    //Relationship with Room_reservations
    public function room_reservations(){
        return $this->belongsTo(Room_reservations::class, 'room_reservation_id');
    }
}
