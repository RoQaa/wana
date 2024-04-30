<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTimeTarget;
use Carbon\Carbon;

class UserTimeTargetController extends Controller
{
       public function recordusrttime(){
           
            UserTimeTarget::create([
                'user_id'=> 55,
            ]);
            
            
            
        
        }
        
            public function EndSet(){
            $timer=   UserTimeTarget::where('user_id',55)->get()->last();
            $timer->updated_at=Carbon::now();
            $timer->min=Carbon::now()->diffInMinutes($timer->created_at);
             $timer->save();
        }
         public function getuserTime($id){
            $timer=   UserTimeTarget::where('user_id',$id)->get()->sum('min');
          return $timer;
          
        }
        
        
        
        
}
