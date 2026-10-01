<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Guests extends Model
{
    use HasFactory;
    use Notifiable;

    //Relationship with Members
    public function member(){
        return $this->hasOne(Members::class, 'guest_id');
    }

    //Relationship with Room_reservations
    public function reserved_by(){
        return $this->hasMany(Room_reservations::class, 'reserved_by_guest_id');
    }

    //Relationship with Room_reservations
    public function payer(){
        return $this->hasMany(Room_reservations::class, 'payer_guest_id');
    }

    //Relationship with Guest_reservations
    public function reservations(){
        return $this->belongsToMany(Room_reservations::class, 'guest_reservations', 'guest_id', 'room_reservation_id');
    }

     //Relationship with Payments
     public function payments(){
        return $this->hasMany(Payments::class, 'payer_guest_id');
    }
}
