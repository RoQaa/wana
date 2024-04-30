<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AchiveModels extends Model
{
    protected $table = 'achive_models';

    protected $fillable = [
        'image',
        'blackimage',
        'name',
        'status',
        'reason','description'
    ];
}
