<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public function book(){
        return $this->belongsTo('App\Models\Book');
    }
}
