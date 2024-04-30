<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class gift extends Model
{
    protected $table = 'gifts';
    protected $fillable = [
        'name',
        'image',
        'category_id',
        'price',
        'state',
         'svga','sound','event_id','luckypackage',
         'lucky'
    ];

}
