<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    function user(){
        $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
