<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class insult extends Model
{
    protected $table = 'insults';

    protected $fillable = [
        'user_id',
        'text',
        'type',
    
    ];
}
