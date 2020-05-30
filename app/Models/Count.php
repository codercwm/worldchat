<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Count extends Model
{
    public static $ROOMLIST = [
        1 => 'room1',
        2 => 'room2'
    ];

    public $timestamps = false;
}
