<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'book_rack_id',
        'rack_layer',
        'floor',
        'location_id',
        'image',
        'title',
        'author',
        'description',
        'duration',
    ];

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function book_rack(){
        return $this->belongsTo('App\Models\Book_Rack');
    }

    public function records(){
        return $this->hasMany('App\Models\Record');
    }

}
