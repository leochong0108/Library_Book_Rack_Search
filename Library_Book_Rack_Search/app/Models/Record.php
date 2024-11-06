<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{

    protected $fillable = [
        'book_id',
        'remark',
        'action',
        'expired_at'
    ];

    public function book(){
        return $this->belongsTo('App\Models\Book');
    }
}
