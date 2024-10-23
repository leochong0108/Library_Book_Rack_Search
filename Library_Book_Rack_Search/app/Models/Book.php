<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function records(){
        return $this->hasMany('App\Models\Record');
    }
}
