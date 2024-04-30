<?php

namespace App\Http\Controllers;
use App\Models\luckybags;
use Validator;
use App\Models\UserApp;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use  App\Events\UserEvent;
use  App\Events\glopel;
use  App\Events\RoomEvent;
use App\Models\Rooms;
class LuckybagsController extends Controller
{  
    use GeneralTrait;

   
 public function  SendLuckybags(Request $request){
       $rules = [
            "user_id" => "required",
            "room_id"=> "required",
            'coins'=> "required",

        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $user=UserApp::where('id',$request->user_id)->first();
         $room =Rooms::find($request->room_id);
        if($request->coins<0||$user->coins<$request->coins||$user->coins==0||$request->coins==0){
           return $this->returnError('E001', 'Can\'t Play Luck');
        }
      $luckybags= luckybags::create([
            'user_id'=> $request->user_id,
           'room_id'=> $request->room_id,
           'coins'=> $request->coins,

        ]);
       
         $user->decrement('coins',$request->coins);
         event(new glopel(3,['user'=>$user,'room'=>$room]));
          sleep(6);
         event(new RoomEvent(28,['user'=> $user,'id'=>$luckybags->id],$request->room_id));
                 return 'done';
   }
   
   
     public function  AcceptLuckybags(Request $request){
         $randomElement=0;
         $ran = array(10,20,30,40);

        
       $rules = [
            "user_id" => "required|exists:user_apps,id",
            "room_id"=> "required|exists:rooms,id",
            'luck_id'=> "required|exists:luckybags,id",

        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $user=UserApp::find($request->user_id);
      $luck=luckybags::where('id',$request->luck_id)->first();
  if($luck->coins>10){
      $randomElement = $ran[array_rand($ran, 1)];
      if($randomElement>$luck->coins){
          
      }else{
             $user->increment('coins',$randomElement );
            $luck->decrement('coins',$randomElement);
         event(new UserEvent(11,['coins'=>$randomElement ],$user->id));
      }
      
  }else{
      
  }
     
      
    
        return $randomElement ;
      
       
       
     }
    
    
}
