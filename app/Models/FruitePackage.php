<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FruitePackage extends Model
{
    protected $table = 'fruite_packages';
    protected $fillable = [
        'value',
    ];
    
    
    public $timestamps = false;
  protected $hidden = ['created_at', "updated_at"];


}
