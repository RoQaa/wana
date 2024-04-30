<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Fruits extends Model
{
    protected $table = 'fruits';
    protected $fillable = [
        'index',
        'value',
        'image'
    ];
    
    public $timestamps = false;
  protected $hidden = ['created_at', "updated_at"];

}
