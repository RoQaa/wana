<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\RoomKarisma;
use Carbon\Carbon;
use DB;
class RoomKarismaController extends Controller
{
       use GeneralTrait;
    public function  GetRoomKarismas($roomid){
$weeklysupporter = RoomKarisma::where('room_id',$roomid)->with('user')->where('created_at', '>=',Carbon::now()->subDays(7))->select("user_id",DB::raw('sum(karisma) as karisma') ) 
->groupBy("user_id")->orderBy('karisma', 'DESC')->take(20)->get();
 
$Monthlysupporter =  RoomKarisma::where('room_id',$roomid)->with('user')->where('created_at', '>=',Carbon::now()->subDays(30))->select("user_id",DB::raw('sum(karisma) as karisma') ) 
->groupBy("user_id")->orderBy('karisma', 'DESC')->take(20)->get();
 
$dailysupporter = RoomKarisma::where('room_id',$roomid)->with('user')->whereDate('created_at', Carbon::today())->select("user_id",DB::raw('sum(karisma) as karisma') ) 
->groupBy("user_id")->orderBy('karisma', 'DESC')->take(20)->get();
 
        return $this->returnData('RoomKarisma',['weeklysupporter'=>$weeklysupporter,'Monthlysupporter'=>$Monthlysupporter,'dailysupporter'=>$dailysupporter]);
  
    }
    
}
