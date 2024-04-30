<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
 
    protected $fillable = [
        'user_id',
        'feedback',
        'contact',
        'feedback_type', 
        'contact_type',
        'image','status'
    ];
}
