<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    use HasFactory;

    //Relationship with User
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    //Relationship with Guests
    public function guest(){
        return $this->belongsTo(Guests::class, 'guest_id');
    }
}
