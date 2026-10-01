<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    //Relationship with User
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
