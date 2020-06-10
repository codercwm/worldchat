<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public static $ROOMLIST = [
        1 => 'room1',
        2 => 'room2'
    ];

    const UPDATED_AT = null;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
