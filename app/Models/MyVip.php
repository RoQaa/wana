<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyVip extends Model
{
    protected $table = 'my_vips';
 
    protected $fillable = [
        'user_id',
        'vip_id',
        'days',
        'cost',
        'new_id',
        'endstatus',
        'Color_message',
        'Hidden',
        'update_level',
        'status'
    ];

    
    public function vip(){
        return $this->hasone(VipCenter::class,"id","vip_id");
    }  
}
