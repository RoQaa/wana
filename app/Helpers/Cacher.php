<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class Cacher{
//    //file //redi
public  $store = 'redis';

    public function setCached($key,$value){
            Redis::set($key,$value);
    }
  public function getCached($key){
    $cachedData =  Redis::get($key);
        if($cachedData){
            return json_decode($cachedData,false);
        }
    }

    public function removeCached($key){
        Redis::del($key);
    }
}
