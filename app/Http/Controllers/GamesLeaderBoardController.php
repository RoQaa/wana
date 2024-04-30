<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\GamesLeaderBoard;
class GamesLeaderBoardController extends Controller
{
     use GeneralTrait;
      public function GamesLeaderBoard(){
             $GamesLeader = GamesLeaderBoard::where('status',1)->get()->sum('resultcoins');
             $lose = GamesLeaderBoard::where('status',0)->get()->sum('resultcoins');

             return ['win'=> $GamesLeader,'lose'=>$lose];
      }
      
      
      
      
     
}
