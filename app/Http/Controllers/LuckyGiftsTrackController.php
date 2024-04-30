<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\LuckyGiftsTrack;
class LuckyGiftsTrackController extends Controller
{
    
      public function  Getusersum($id){
         $sum= LuckyGiftsTrack::where('user_id',$id) ->where('created_at', '>=',Carbon::now()->subDays(1))
->get()->sum('coins'); 
         $sum2= LuckyGiftsTrack::where('user_id',$id) ->where('created_at', '>=',Carbon::now()->subDays(1))
->get()->sum('wincoins'); 
 
         return ['coins'=>$sum,'win'=>$sum2];
          
      }
}
