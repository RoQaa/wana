<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    protected $table = 'shop_items';

    protected $fillable = [
        'name',
        'status',
        'svggift',
        'imagegift',
        'shopcategory_id','price',
        'month',
        'day',
        'kind',
        'important'
    ];


   
}
