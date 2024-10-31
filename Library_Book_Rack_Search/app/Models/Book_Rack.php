<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book_Rack extends Model
{
    public function books(){
        return $this->hasMany('App\Models\Book');
    }
}
