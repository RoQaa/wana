<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class emojicategory extends Model
{
    protected $table = 'emojicategories';
    protected $fillable = [
        'name',
        'status',
        'sorting'
    ];

    public function emoji(){
        return $this->hasmany(emoji::class,"category_id","id");
    }


}
