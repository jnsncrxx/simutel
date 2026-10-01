<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room_reservations extends Model
{
    use HasFactory;

    //Relationship with Guests
    public function reserved_by(){
        return $this->belongsTo(Guests::class, 'reserved_by_guest_id');
    }

    //Relationship with Guests
    public function payer(){
        return $this->belongsTo(Guests::class, 'payer_guest_id');
    }

    //Relationship with Guest_reservations
    public function guests(){
        return $this->belongsToMany(Guests::class, 'guest_reservations', 'room_reservation_id', 'guest_id');
    }

    //Relationship with payments
    public function payments(){
        return $this->hasMany(Payments::class, 'room_reservation_id');
    }

    //Relationship with additional_charges
    public function additional_charges(){
        return $this->hasMany(Additional_charges::class, 'room_reservation_id');
    }
    //Relationship with cancellations
    public function cancellations(){
        return $this->hasOne(Cancellations::class, 'room_reservation_id');
    }
}
