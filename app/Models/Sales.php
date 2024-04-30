<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'item_id',
        'user_id',
        'end_time',
        'status',
        'price',
        'day',
        'category_id'
    ];


    public function item(){
        return $this->hasone(ShopItem::class,"id","item_id");
    }
}
