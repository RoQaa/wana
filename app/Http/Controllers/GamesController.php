<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\games;
 use App\Models\UserApp;
use Validator;
use App\Traits\GeneralTrait;
class GamesController extends Controller
{
    use GeneralTrait;
 public function getuser(){
   $user= UserApp::where('id',8468)->select('id','name','coins','myappid')->first();
 return $this->returnData('user', $user);
}
    
    public function GetAllGames(){
    $games=games::where('status',1)->paginate(15); 
     return $this->returnData('games',$games);
    }
    public function incrementuser($id){
        $games=games::where('id',$id)->increment('usersnumber', 1); 
        
        }
}
