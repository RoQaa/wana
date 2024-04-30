<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FamilyAdmins extends Model
{
    protected $table = 'family_admins';

    protected $fillable = [
        'Family_id',
        'user_id',
        
    ];
}
