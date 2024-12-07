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
        'is_available',
    ];

    protected $appends = ['image_path'];

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function bookRack(){
        return $this->belongsTo('App\Models\bookracks');
    }

    public function records(){
        return $this->hasMany('App\Models\Record');
    }

    public function getImagePathAttribute(){
        if(!$this->image){
            return null;
        }

        return '/storage/images/'.$this->image;
    }
}
