<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCategory extends Model
{
    protected $table = 'gift_categories';
    protected $fillable = [
        'name',
        'state',
        'sorting','lucky','vip'
    ];

    public function gifts(){
        return  $this->hasmany(gift::class,'category_id','id')->where('status',1)->orderBy('sorting', 'DESC');
    }

}
