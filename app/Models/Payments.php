<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    //Relationship with Room_reservations
    public function room_reservations(){
        return $this->belongsTo(Room_reservations::class, 'room_reservation_id');
    }

    //Relationship with Employees
    public function guests(){
        return $this->belongsTo(Guests::class, 'payer_guest_id');
    }
}
