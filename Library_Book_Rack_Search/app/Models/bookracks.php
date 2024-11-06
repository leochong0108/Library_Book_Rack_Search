<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bookracks extends Model
{
    protected $fillable = [
        'rack_layer',
        'floor',
        'range',
    ];

    public function books(){
        return $this->hasMany('App\Models\Book');
    }
}
