<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class roomcategory extends Model
{
    protected $table = 'roomcategories';
 
    protected $fillable = [
        'name',
    ];
}
