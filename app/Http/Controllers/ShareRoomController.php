<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\ShareRoom;
use  App\Events\UserEvent;
use App\Models\follow;
use App\Models\UserApp;
use App\Models\Rooms;
class ShareRoomController extends Controller
{
        use GeneralTrait;
    public function SentShareRoom(Request $request){
          $Room=Rooms::where('id',$request->room_id)->first(['name','id','admin_id']);
           
           $user= UserApp::where('id',$Room->admin_id)->first(['name','id']);
         
        try {
        $rules = [
            "user_id"=>"required",
            "room_id"=>"required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
           $ShareRoom = ShareRoom::create([
            'user_id'=> $request->user_id,
            'room_id'=> $request->room_id,
        ]);
        if($ShareRoom){
           event(new UserEvent(7,['Room'=> $Room,'user'=> $user,],$request->user_id));
            return $this->returnData('ShareRoom',$ShareRoom);
            }else{
                return $this->returnError('E001', 'Can\'t add ShareRoom');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    
    
      public function SentInviteChair(Request $request){
          $Room=Rooms::where('id',$request->room_id)->first(['name','id','admin_id']);
             
           $user= UserApp::where('id',$Room->admin_id)->first(['name','id']);
        
        try {
        $rules = [
            "user_id"=>"required",
            "room_id"=>"required",
             "chair_id"=>"required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }else{
                       event(new UserEvent(8,['Room'=> $Room,'user'=> $user,'chair_id'=>$request->chair_id],$request->user_id
                       ));

        }
         
      
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    
    
    
public function GetSharefrinds($id,$Roomid){
    try {
      
$ShareIds= ShareRoom::where('room_id',$Roomid)->get()->pluck('user_id');  
 
$followers=follow::where([['sender_id',$id],['status',1]])->orwhere([['user_id',$id],['status',1]])->with('user','otheruser')->get();


return ['Follow'=>$followers,'ShareIds'=>$ShareIds];

 
      
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}


 

}
