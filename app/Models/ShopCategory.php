<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    protected $table = 'shop_categories';

    protected $fillable = [
        'name',
        'status',
        'cp',
    ];
    public function items(){


        return $this->hasmany(ShopItem::class,'shopcategory_id','id')->orderBy('sorting', 'desc')->where('status',1);
         }
         public function sales(){

 
            return $this->hasmany(Sales::class,'category_id','id');
             } 
    
}
