<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;

use Illuminate\Http\Request;
use App\Models\Joinroom;
use App\Models\Kickedusers;
use App\Models\Rooms;
use App\Models\UserApp;
use Storage as ss;
use  App\Events\RoomEvent;
use App\Models\Chairs;
use App\Models\chatroom;
use App\Models\FollowRoom;
use App\Models\UserTimeTarget;
 use Carbon\Carbon;
use App\Http\Controllers\RtcTokenBuilder2;
class JoinroomController extends Controller
{
    use GeneralTrait;
    
      
      public function EndSet($id){
            $timer=   UserTimeTarget::where('user_id',$id)->get()->last();
            if($timer!=null){
                 $timer->updated_at=Carbon::now();
            $timer->min=Carbon::now()->diffInMinutes($timer->created_at);
             $timer->save();
            }
           
        }
    
 public function JoinRoom(Request $request){
    try {
    $rules = [
        "user_id" => "required|exists:user_apps,id",
        "room_id" => "required|exists:rooms,id",
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
    $chair=Chairs::where('user_id',$request->user_id)->get();
    foreach($chair as $row) {
        $row->Karisma = 0;  
        $row->save();
      }
     $user=UserApp::where('id',$request->user_id)->with('myvip')->first();
      if(  $user->phone_number==null&&$user->email==null){
                   return $this->returnError(123,'Cant remove entry');
            }
            

     $Kicked=Kickedusers::where('user_id',$request->user_id)->where([['room_id',$request->room_id],['state',0]])->first();
     if($Kicked){
        return $this->returnError('E115', 'You Are Kicked');
     }
     $this->Removeroom($request->user_id);
     $Room=Rooms::where('id',$request->room_id)->with('joinRoom.user','admin','chairs.user','supervisors.user')->first();
     $chatroom=[];
     if($Room){ 
        $join = Joinroom::create([
        'room_id'=>$request->room_id,  
        'user_id'=>$request->user_id,      
        ]);
        $this->EndSet($request->user_id);
            if($Room->admin_id==$request->user_id){
            
            //  $Adminchair=Chairs::where([['user_id',$request->user_id],['chair_id',9]]);
               UserTimeTarget::create([
                'user_id'=> $request->user_id,
            ]);
        // if($Adminchair){
        // $Adminchair->update(array('adminleaved'=>0,'joindate'=>Carbon::now()));
        //   }
        }
         Rooms::where('id',$request->room_id)->increment('user_number', 1);
        $FollowRoom=FollowRoom::where([['user_id',$request->user_id],['room_id',$request->room_id]])->first();
     
         if($FollowRoom!=null){
              $Room->FollowRoom=1;
         }else{
              $Room->FollowRoom=0;
         }
        $Room->chatroom=$chatroom;
}else{
    return $this->returnError('E112', 'Room Not found');
}
$Room->joinid= $join->id;
if($request->user_id==$Room->admin_id){
 $Room->Token=$this->gettoken($request->user_id,$Room->agoratoken,1);
}else{
 $Room->Token=$this->gettoken($request->user_id,$Room->agoratoken,0);
}
    if($join){
        event(new RoomEvent(0,$user,$request->room_id));
        return $this->returnData('Room',$Room);
        }else{
 return $this->returnError('E001', 'Can\'t join this room'); 
}
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}

public function Getjoinusers(Request $request){
    try {
        $rules = [
       
            "room_id"=>"required",
        
                 ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $join=Joinroom::where([['room_id',$request->room_id],['state',0]])->with('user.giftssent.gift','user.giftscollect.gift','user.myvip.vip')->get();
        
       
        
       
        if($join){ 
          return $this->returnData('join', $join);
           }else{
               return $this->returnError('E001', 'Can\'t getjoin');
         }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
}

public function Removeroom($id){
    $Joinroom=Joinroom::where([['user_id',$id],['state',0]])->get();
    foreach($Joinroom as $row){
      $row->state = 1;
      $row->index = 1;
       $user=UserApp::where('id',$row->user_id)->first();
      event(new RoomEvent(2, $user,$row->room_id));
      $row->save();
    }
$Chairs=Chairs::where('user_id',$id)->get();
foreach($Chairs as $row) {
    $row->  user_id = null;  
    $row->  Karisma = 0; 
    $row->save(); 
  }
      }

public function Removeadminroom(Request $request){
    $Joinroom=Joinroom::where([['user_id',$request->user_id],['state',0],['room_id',$request->room_id]])->update(['state'=>1,'index'=>1]);
    
    $Kickedusers = Kickedusers::create([
        'room_id'=>$request->room_id,  
        'user_id'=>$request->user_id,       
        ]);
    event(new RoomEvent(10,$request->user_id,$request->room_id));
    return $Joinroom;

      }


 public function JoinChair(Request $request){

    try {
        $rules = [
            "user_id" => "required|exists:user_apps,id",
            "chair_id" => "required",
            "room_id" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()){
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $chair=Chairs::where([['chair_id',$request->chair_id],['user_id',null],['room_id',$request->room_id]])->first();
        
      
        if($chair==null){

            return $this->returnError('E115', 'Can\'t join this Chair');

        }
        
       $user=UserApp::where('id',$request->user_id)->get();
       $chair->user_id=$request->user_id;
       $chair->joindate= Carbon::now();
       
       UserTimeTarget::create([
                'user_id'=> $request->user_id,
            ]);
       $chair->save();
    
       $chair=Chairs::where([['chair_id',$request->chair_id],['user_id',$request->user_id]])->with('user')->first();
       $join=Joinroom::where([['user_id',$request->user_id],['room_id',$request->room_id]])->update(['index'=>1]);
       $Room=Rooms::where('id',$request->room_id)->where('state',0)->with('joinRoom.user','admin','chairs.user')->first();
  
        if($chair){
          
            event(new RoomEvent(1, $chair, $Room->id));
         
            return $this->returnData('Room',$chair);
            }else{
                return $this->returnError('E001', 'Can\'t join this room');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
 }
 
 
 

public function LeaveRoom(Request $request){
    try {
    $rules = [
        "join_id" => "exists:joinrooms,id",
        "room_id"=>"required",
        "user_id"=>"required",
             ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
     $user=UserApp::where('id',$request->user_id)->first();
    $join=Joinroom::where('id',$request->join_id)->update(array('state' => '1','index'=>null));
    $chair=Chairs::where('user_id',$request->user_id);
       $Room=Rooms::where('id',$request->room_id)->first();
    if($chair){
        $chair->update(array('user_id'=>null,'Karisma'=>0));
    }
    //     if($Room->admin_id==$request->user_id){
            
    //          $Adminchair=Chairs::where([['user_id',$request->user_id],['chair_id',9]]);
    //  if($Adminchair){
    //     $Adminchair->update(array('adminleaved'=>1));
        
    // }
    //     }
    
    
   if($join){
       
        $this->EndSet($request->user_id);
       $Room->decrement('user_number',1);

      event(new RoomEvent(2,$user ,$request->room_id));
      return $this->returnData('join',$join);
       }else if($request->join_id==null){
        $Room=Rooms::where('id',$request->room_id)->first();
        $Room->user_number--;
        $Room->save();
    
        event(new RoomEvent(2, $user,$request->room_id));
          return $this->returnData('join',$join);
       }else{
           return $this->returnError('E001', 'Can\'t LeaveRoom');
     }
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}

public function xxx(Request $request){
   

    if($request->all()['events'][0]['name']=='channel_vacated'){
         
         $chair=Chairs::where('user_id',$request->all()['events'][0]['channel']);
         
             $this->Removeroom($request->all()['events'][0]['channel']);
    $user=UserApp::where('id',$request->all()['events'][0]['channel'])->first();

       $joindata=Joinroom::where([['user_id',$request->all()['events'][0]['channel']],['state','0']])->first();
  
  
   if( $joindata!=null){
       
       
       
         $Room=Rooms::where('id',$joindata->room_id);
          ss::put('file.txt','xxxxxxx');
         if(  $Room!=null){
              $Room->decrement('user_number', 1); 
                event(new RoomEvent(6,$user,$joindata->room_id));
         }
        
   }
       
   }
  
    
    
      $this->EndSet($request->all()['events'][0]['channel']);
        
        
    if($chair){
        $chair->update(array('user_id'=>null));
        $chair->update(array('Karisma'=>0));
    
     //  ss::put('file.txt',$request->all()['events'][0]['channel'].'out');
    }else{
      //   ss::put('file.txt',$request->all()['events'][0]['channel'].'in');
    }
      
               
}

    //   public function Removeroom($id){

    // $Joinroom=Joinroom::where([['user_id',$id],['state',0]])->get();
  
    // foreach($Joinroom as $row) {
    //   $row->state = 1;  
    //   $row->index = 1;
    //   event(new RoomEvent(16, $row->user_id,$row->room_id));
    //   $row->save();
    // }}
public function Leaveapp($user_id,$AppPassword){
      
    try {
        
       if($AppPassword!='AcrQW41!-*')
       {
        return 'Error';
       }
    $user=UserApp::where('id',$user_id)->first();
  $this->EndSet($user->id);
    $joindata=Joinroom::where([['user_id',$user_id],['state','0']])->first();
 
    $Room=Rooms::where('id',$datas->room_id)->decrement('user_number', 1); 
    
    
    $chair=Chairs::where('user_id',$user_id);
    
    if($chair){
        $chair->update(array('user_id'=>null));
        $chair->update(array('Karisma'=>0));
    }
    
    $join= $joindata->update(array('state' => '1','index'=>null));
    event(new RoomEvent(6,$user,$datas->room_id));

   return $this->returnData('room',$user_id);
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}


public function LeaveChair(Request $request){
 
    try {
        $rules = [
            "chair_id" =>"required",
            "room_id"=>"required",
            "user_id"=>"required",
         ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        
        
         
        
        $chair=Chairs::where([['user_id',$request->user_id] ,['room_id',$request->room_id],['chair_id',$request->chair_id]])->first();
       
     

        
        if($chair){
        
         $chair->update(array('user_id'=>null,'Karisma'=>0));
           
        }
        $this->EndSet($request->user_id);

       if($chair){

              event(new RoomEvent(3,$request->user_id,$request->room_id));

              return $this->returnData('chair',$chair);
           }else{
               return $this->returnError('E001', 'Can\'t LeaveChair');
         
             }
        } catch (\Exception $ex) {

            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
  
}

public function ChangeChair(Request $request){
 
    try {
        $rules = [
            "Current_chair" =>"required",
            "chair_id" =>"required",
            "room_id"=>"required",
            "user_id"=>"required",
         ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
         


        $chair=Chairs::where('id',$request->chair_id )->first();

       $user=UserApp::where('id',$request->user_id)->first();
       if($chair->user_id!=null){
           
          return $this->returnError($ex->getCode(), $ex->getMessage());
       }else{
            $chairCurrent=Chairs::where('id',$request->Current_chair)->first();


 if($chairCurrent){
  $chairCurrent->update(array('user_id'=>null,'Karisma'=>0,'joindate'=>null));
 }
       }

       if($chair){
        
         $chair->update(array('user_id'=>$request->user_id,'Karisma'=>0,'joindate'=>Carbon::now()));
           
        }

 

       if($chair){

             event(new RoomEvent(19,['user'=>$user,'Currentchair'=>$chairCurrent->chair_id,'chair_id'=>$chair->chair_id],$request->room_id));

              return $this->returnData('chair',$chair);
           }else{
               return $this->returnError('E001', 'Can\'t LeaveChair');
         
             }
        } catch (\Exception $ex) {

            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
  
}
 
public function updatemutechair(Request $request){
    try {

    $rules = [
        
            'user_id' => 'required',
'room_id' => 'required',
'state'=>'required',
 
                 ]; 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
$Chair=Chairs::where([['room_id',$request->room_id],['user_id',$request->user_id]])->update(array('mute' => $request->state));

$Chairdata=Chairs::where([['chair_id',$request->chair_id],['user_id',$request->user_id]]);
    event(new RoomEvent(8,['userid'=>$request->user_id,'state'=>$request->state],$request->room_id));

   return $this->returnData('mute',$request->user_id);
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}


public function Evictionuser(Request $request){
    try {
        $rules = [
            'room_id' => 'required|exists:rooms,id',
            'user_id' => 'required',
 
                 ]; 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

    $user=UserApp::where('id',$request->user_id)->first();
      $this->EndSet($user->id);
  $Room=Rooms::where('id',$request->room_id)->first();
  if($Room->admin_id==$request->user_id){
      return 'fucks';
  }

    $join=Joinroom::where([['room_id',$request->room_id],['user_id',$request->user_id],['state','0']])->update(array('state' => '1','index'=>null));
    $chair=Chairs::where('user_id',$request->user_id);
    if($chair){
        $chair->update(array('user_id'=>null));
        $chair->update(array('Karisma'=>0));
    }
    $Kickedusers = Kickedusers::create([
        'room_id'=>$request->room_id,  
        'user_id'=>$request->user_id,       
        ]);
    event(new RoomEvent(6,$user,$request->room_id));

   return $this->returnData('room',Rooms::find($request->room_id));
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}

}

public function DisbandRoom(Request $request){

    try {
        $rules = [
            'room_id' => 'required|exists:rooms,id',
                 ]; 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    $join=Joinroom::where('room_id',$request->room_id)->update(array('state' => '1','index'=>null));
    $room=Rooms::where('id',$request->room_id)->update(array('state' => '1'));
    $room=Rooms::where('id',$request->room_id)->first();
           $Adminchair=Chairs::where([['room_id',$request->room_id],['chair_id',9]]);
     if($Adminchair){
        $Adminchair->update(array('adminleaved'=>1));
        
    }
    event(new RoomEvent(7,['room'=>$room],$request->room_id));
   return $this->returnData('room',Rooms::find($request->room_id));
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}

}


public function DisbandoldRoom($room_id){

    try {
       
    $join=Joinroom::where('room_id',$room_id)->update(array('state' => '1','index'=>null));
    $room=Rooms::where('id',$room_id)->update(array('state' => '1'));
    $room=Rooms::where('id',$room_id)->first();
          $Adminchair=Chairs::where([['room_id',$room_id],['chair_id',9]]);
     if($Adminchair){
        $Adminchair->update(array('adminleaved'=>1));
        
    }
    event(new RoomEvent(7,['room'=>$room],$room_id));
   return $this->returnData('room',Rooms::find($room_id));
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}

}


 public function DisbandRoom2(Request $request){
     if($request->secure!='436uj80q1'){
         return 'fuck';
     }

    try {
        $rules = [
            'room_id' => 'required|exists:rooms,id',
                 ]; 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    $join=Joinroom::where('room_id',$request->room_id)->update(array('state' => '1','index'=>null));
    $room=Rooms::where('id',$request->room_id)->update(array('state' => '1'));
    $room=Rooms::where('id',$request->room_id)->first();
    //       $Adminchair=Chairs::where([['room_id',$request->room_id],['chair_id',9]]);
    //  if($Adminchair){
    //     $Adminchair->update(array('adminleaved'=>1));
        
    // }
    event(new RoomEvent(7,['room'=>$room,'admin'=>'0'],$request->room_id));
   return $this->returnData('room',Rooms::find($request->room_id));
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}

}
public function DisbandRoomAdmin(Request $request){

    try {
        $rules = [
            'room_id' => 'required|exists:rooms,id',
            'token'  =>  'required',
                 ]; 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        if($request->token!='043kjok'){
            return 'error';
        }
    $join=Joinroom::where('room_id',$request->room_id)->update(array('state' => '1','index'=>null));
    $room=Rooms::where('id',$request->room_id)->update(array('state' => '1'));
    $room=Rooms::where('id',$request->room_id)->first();
    
    event(new RoomEvent(7,$room,$request->room_id));
   return $this->returnData('room',Rooms::find($request->room_id));
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}

}

public function DeleteRoomChat(Request $request){
    try {
        $rules = [
            'room_id' => 'required|exists:rooms,id',
                 ]; 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
 
    
    event(new RoomEvent(13,13,$request->room_id));
   return $this->returnData('room','done');
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
function  gettoken($uid,$channelName,$ROLE){

    $appId = "08ab6ff57ac54a68a9e995c1a36aa055";
$appCertificate = "fe07afc2b9be4d13abf371609d3d30df";

$tokenExpirationInSeconds = 86400; 
$privilegeExpirationInSeconds = 86400; 

$token = RtcTokenBuilder2::buildTokenWithUid($appId, $appCertificate, $channelName, $uid,$ROLE  , $tokenExpirationInSeconds, $privilegeExpirationInSeconds);
return $token;
 
 }
}
