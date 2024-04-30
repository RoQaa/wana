<?php

namespace App\Http\Controllers;
use App\Models\Leaderboard;
use App\Models\starsevent;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use DB;
use App\Traits\GeneralTrait;

class LeaderboardController extends Controller
{
    use GeneralTrait;

    public function GetsupporterWeeklyStar(){
$starsevent=starsevent::with('Gifts')->first();
$supporter= Leaderboard::where([['status',1],['event_id',$starsevent->id]])->with('user')->where('created_at', '>=',$starsevent->Starttime)
->where('created_at', '<=', $starsevent->Endtime)->select("user_id",DB::raw('sum(coins) as coins') ) 
->groupBy("user_id")->orderBy('coins', 'DESC')->take(10)->get();
$supported= Leaderboard::where([['status',2],['event_id',$starsevent->id]])->with('user')->where('created_at', '>=',$starsevent->Starttime)
->where('created_at', '<=', $starsevent->Endtime)->select("user_id",DB::raw('sum(coins) as coins') ) 
->groupBy("user_id")->orderBy('coins', 'DESC')->take(10)->get();
$starsevent->supporters=$supporter;
$starsevent->supporteds=$supported;
return $starsevent;
    }



    public function GetRoomLeaderboard(){

        try {
    
  
  
$dailyRoom = Leaderboard::where('status',3)->with('room')->whereDate('created_at', Carbon::today())->select("room_id",DB::raw('sum(coins) as coins') ) 
->groupBy("room_id")->orderBy('coins', 'DESC')->take(20)->get();
 $weeklyRoom = Leaderboard::where('status',3)->with('room')->where('created_at', '>=',Carbon::now()->subDays(7))->select("room_id",DB::raw('sum(coins) as coins') ) 
->groupBy("room_id")->orderBy('coins', 'DESC')->take(20)->get();
$MonthlyRoom = Leaderboard::where('status',3)->with('room')->where('created_at', '>=',Carbon::now()->subDays(30))->select("room_id",DB::raw('sum(coins) as coins') ) 
->groupBy("room_id")->orderBy('coins', 'DESC')->take(20)->get();
         
    //whereBetween('created_at', [Carbon::today(), Carbon::today()->addDays(7)]) 
        
      // return $this->returnData('Leaderboard',['supporter'=>['weeklysupporter'=>$weeklysupporter,'Monthlysupporter'=>$Monthlysupporter,'dailysupporter'=>$dailysupporter],'Recipient'=>['weeklysupporter'=>$weeklyRecipient,'Monthlysupporter'=>$MonthlyRecipient,'dailysupporter'=>$dailyRecipient],'Room'=>['dailysupporter'=>$dailyRoom,'weeklysupporter'=>$weeklyRoom,'Monthlysupporter'=>$MonthlyRoom] ]);
      return $this->returnData('Leaderboard',['Room'=>['dailysupporter'=>$dailyRoom,'weeklysupporter'=>$weeklyRoom,'Monthlysupporter'=>$MonthlyRoom]] );
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }

     public function GetFamilyLeaderboard(){

        try {
    
  
  
$dailyFamily = Leaderboard::where('status',4)->with('family')->whereDate('created_at', Carbon::today())->select("family_id",DB::raw('sum(coins) as coins') ) 
->groupBy("family_id")->orderBy('coins', 'DESC')->take(20)->get();
 $weeklyFamily = Leaderboard::where('status',4)->with('family')->where('created_at', '>=',Carbon::now()->subDays(7))->select("family_id",DB::raw('sum(coins) as coins') ) 
->groupBy("family_id")->orderBy('coins', 'DESC')->take(20)->get();
$MonthlyFamily = Leaderboard::where('status',4)->with('family')->where('created_at', '>=',Carbon::now()->subDays(30))->select("family_id",DB::raw('sum(coins) as coins') ) 
->groupBy("family_id")->orderBy('coins', 'DESC')->take(20)->get();
         
return $this->returnData('Leaderboard',['Family'=>['dailyFamily'=>$dailyFamily,'weeklyFamily'=>$weeklyFamily,'MonthlyFamily'=>$MonthlyFamily]] );
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }


     public function GetGiverLeaderboard(){

        try {
    
  
 
$weeklysupporter = Leaderboard::where('status',1)->with('user')->where('created_at', '>=',Carbon::now()->subDays(7))->select("user_id",DB::raw('sum(coins) as coins') ) 
->groupBy("user_id")->orderBy('coins', 'DESC')->take(20)->get();
$Monthlysupporter = Leaderboard::where('status',1)->with('user')->where('created_at', '>=',Carbon::now()->subDays(30))->select("user_id",DB::raw('sum(coins) as coins') ) 
->groupBy("user_id")->orderBy('coins', 'DESC')->take(20)->get();
$dailysupporter = Leaderboard::where('status',1)->with('user')->whereDate('created_at', Carbon::today())->select("user_id",DB::raw('sum(coins) as coins') ) 
->groupBy("user_id")->orderBy('coins', 'DESC')->take(20)->get();
 
 
  
        
       return $this->returnData('Leaderboard',['supporter'=>['weeklysupporter'=>$weeklysupporter,'Monthlysupporter'=>$Monthlysupporter,'dailysupporter'=>$dailysupporter]  ]);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }

     public function GetReciverLeaderboard(){

        try {
    
  
 
 
 
 $weeklyRecipient = Leaderboard::where('status',2)->with('user')->where('created_at', '>=',Carbon::now()->subDays(7))->select("user_id",DB::raw('sum(coins) as coins') ) 
->groupBy("user_id")->orderBy('coins', 'DESC')->take(20)->get();
$MonthlyRecipient = Leaderboard::where('status',2)->with('user')->where('created_at', '>=',Carbon::now()->subDays(30))->select("user_id",DB::raw('sum(coins) as coins') ) 
->groupBy("user_id")->orderBy('coins', 'DESC')->take(20)->get();
$dailyRecipient = Leaderboard::where('status',2)->with('user')->whereDate('created_at', Carbon::today())->select("user_id",DB::raw('sum(coins) as coins') ) 
->groupBy("user_id")->orderBy('coins', 'DESC')->take(20)->get();
 
    //whereBetween('created_at', [Carbon::today(), Carbon::today()->addDays(7)]) 
        
      // return $this->returnData('Leaderboard',['supporter'=>['weeklysupporter'=>$weeklysupporter,'Monthlysupporter'=>$Monthlysupporter,'dailysupporter'=>$dailysupporter],'Recipient'=>['weeklysupporter'=>$weeklyRecipient,'Monthlysupporter'=>$MonthlyRecipient,'dailysupporter'=>$dailyRecipient],'Room'=>['dailysupporter'=>$dailyRoom,'weeklysupporter'=>$weeklyRoom,'Monthlysupporter'=>$MonthlyRoom] ]);
       return $this->returnData('Leaderboard',[ 'Recipient'=>['weeklysupporter'=>$weeklyRecipient,'Monthlysupporter'=>$MonthlyRecipient,'dailysupporter'=>$dailyRecipient] ]);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }
     



      public function AddLeaderboard(Request $request){
            try {
            $rules = [
               
                "user_id"=> "required",
                'status'=> "required",
                'coins'=> "required",
              

            ];
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
           

               $Leaderboard = Leaderboard::create([
                'user_id'=> $request->user_id,
                'status'=> $request->status,
                'coins'=> $request->coins,
             
            ]);
            if( $Leaderboard){
                return $this->returnData('Leaderboard', $Leaderboard);
                }else{
                    return $this->returnError('E001', 'Can\'t add Leaderboard');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }  


        public function GetFamilyStar($id){

            try {
        
      
     
    $weeklysupporter = Leaderboard::where([['family_id',$id],['status','4']])->with('user')->where('created_at', '>=',Carbon::now()->subDays(7))->select("user_id",DB::raw('sum(coins) as coins') ) 
    ->groupBy("user_id")->orderBy('coins', 'DESC')->take(20)->get();
    $Monthlysupporter = Leaderboard::where([['family_id',$id],['status','4']])->with('user')->where('created_at', '>=',Carbon::now()->subDays(30))->select("user_id",DB::raw('sum(coins) as coins') ) 
    ->groupBy("user_id")->orderBy('coins', 'DESC')->take(20)->get();
    $dailysupporter = Leaderboard::where([['family_id',$id],['status','4']])->with('user')->whereDate('created_at', Carbon::today())->select("user_id",DB::raw('sum(coins) as coins') ) 
    ->groupBy("user_id")->orderBy('coins', 'DESC')->take(20)->get();
     
     
      
            
           return $this->returnData('Leaderboard',['supporter'=>['weeklysupporter'=>$weeklysupporter,'Monthlysupporter'=>$Monthlysupporter,'dailysupporter'=>$dailysupporter]  ]);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
         }

         public function GetTopStar($id){

            try {
        
      
     
    $TopStar = Leaderboard::where('family_id',$id)->with('user')->select("user_id",DB::raw('sum(coins) as coins') ) 
    ->groupBy("user_id")->orderBy('coins', 'DESC')->take(10)->get();
    
     
     
      
            
           return $this->returnData('Leaderboard',$TopStar);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
         }
  public function AgencysLeaderBoard(){

        try {
    
  
 
 
 
 $Supporter = Leaderboard::where([['status',1],['agency_id','!=',null]])->with('Agency')->where('created_at', '>=',Carbon::now()->subDays(30))->select("agency_id",DB::raw('sum(coins) as coins') ) ->groupBy("agency_id")->orderBy('coins', 'DESC')->take(20)->get();
 $Supported = Leaderboard::where([['status',2],['agency_id','!=',null]])->with('Agency')->where('created_at', '>=',Carbon::now()->subDays(30))->select("agency_id",DB::raw('sum(coins) as coins') ) ->groupBy("agency_id")->orderBy('coins', 'DESC')->take(20)->get();
 
       return $this->returnData('Leaderboard',[ 'Supporter'=> $Supporter, 'Supported'=> $Supporter, ]);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }
    


}
