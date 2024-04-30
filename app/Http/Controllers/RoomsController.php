<?php

namespace App\Http\Controllers;
use App\Traits\GeneralTrait;
use Validator;
use App\Models\Rooms;
use Illuminate\Http\Request;
use App\Models\Chairs;
use  App\Events\RoomEvent;
use App\Http\Controllers\RtcTokenBuilder2;
use File;
use DB;
use App\Models\UserApp;
use App\Models\Joinroom;
use  App\Events\glopel;
class RoomsController extends Controller
{  
    use GeneralTrait;
//-------------------------------Create Room

public function Roomsimage(){
       $Rooms = Rooms::where('state',0)->update(['image'=>'123321.jpeg','name'=>'wanasah','animateimage'=>'123321.jpeg']);
}



public function SearchRoom($tittle){

    $Rooms = Rooms::where([['RoomID','like','%'.$tittle.'%'],['state',0]])->orwhere([['name','like','%'.$tittle.'%'],['state',0]])->orderBy('user_number','DESC')->get();
    return $this->returnData('Rooms', $Rooms );
  }
  
  public function GetFixedRoom(){

    $Rooms = Rooms::where([['fixed','!=',0],['state',0]])->get();
    return $this->returnData('Rooms', $Rooms );
  }
  
  public function SearchwebRoom($tittle){

    $Rooms = Rooms::where([['RoomID','like','%'.$tittle.'%'],['state',0]])->orwhere([['name','like','%'.$tittle.'%'],['state',0]])->with('admin')->orderBy('user_number','DESC')->get();
    return $this->returnData('Rooms', $Rooms );
  }
public function Getupdateroombumber(){
 
    try {

    $Rooms=Rooms::where('state',0)->with('joinRoom')->get();

    for($i = 0;$i<count($Rooms);$i++)
{
  
    $Rooms[$i]->user_number=count($Rooms[$i]->joinRoom);
  $Rooms[$i]->save();
}
   
   return $this->returnData('Rooms', $Rooms);
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
public function CheckPasswordRoom($id){

    $Rooms = Rooms::where('id',$id)->first();
    if($Rooms->password==null){
          return $this->returnError(404, 'haspassword');
    }else{
         return $this->returnData('password',$Rooms->password);
    }
   
  }

public function CheckPasswordRoomnew($id){

    $Rooms = Rooms::where('id',$id)->first();
    
    if($Rooms->password==null){
          return $this->returnError(404, 'haspassword');
    }else{
         return $this->returnData('password',$Rooms->password);
    }
   
  }
  
  public function CheckPasswordright($id,$pass){

    $Rooms = Rooms::where('id',$id)->first();
     
    if($Rooms->password!=$pass){
          return $this->returnError(404, 'haspassword');
    }else{
         return $this->returnData('password',$Rooms->password);
    }
   
  }
  

public function AddRoom(Request $request){

   $random = str_random(11);
   $RoomID=str_random(6);
    try {
    $rules = [
        "name" => "required|unique:rooms,name,1,state",
         'image' => 'required|image|mimes:jpg,png,jpeg|max:3048',
        "password" => "min:6",
        "admin_id" => 'required|exists:user_apps,id',
        "Category"=>  "required",
        "city"=>  "required",

    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
    $rooms = Rooms::where([['admin_id',$request->admin_id],['state',0]])->first();
 $admin=UserApp::where('id',$request->admin_id)->first();
  $FamilyId=null;
 if($admin->FamilyId!=null){
      $FamilyId=$admin->FamilyId;
 }
 
    if($rooms!=null){
        return $this->returnError('E111', 'Can\'t add room');
    } 

    $image='';
    if($request->hasfile('image')){
        $fileName =time().'.png';   
        $file1 = $request->image->move(public_path('images'),$fileName);
        $image=$fileName;
       }
    $tokenagora=   $this->gettoken($request->admin_id,$random ,1);
       $rooms = Rooms::create([
        'image'=> '123321.jpeg',
        'animateimage'=> '123321.jpeg',
        'name'=>$request->name,
        'password'=>$request->password,
        'admin_id'=>$request->admin_id,
        "Category"=>$request->Category,
        "city"=>$admin->city,
        "Token"=>$tokenagora,
        "agoratoken"=>$random,
        "RoomID"=> $admin->Newid??$admin->myappid,
        "FamilyId"=> $FamilyId,
        "RoomAds"=>$request->RoomAds,
    ]);
    $Chair=Chairs::insert([
        [
            'user_id'=>null,
            'room_id'=>$rooms->id,
            'chair_id'=>1,
    
        ],
        [
            'user_id'=>null,
            'room_id'=>$rooms->id,
            'chair_id'=>2,
    
        ],
        [
            'user_id'=>null,
            'room_id'=>$rooms->id,
            'chair_id'=>3,
        ],
        [
            'user_id'=>null,
            'room_id'=>$rooms->id,
            'chair_id'=>4,
    
        ],
        [
            'user_id'=>null,
            'room_id'=>$rooms->id,
            'chair_id'=>5,
    
        ],
        [
            'user_id'=>null,
            'room_id'=>$rooms->id,
            'chair_id'=>6,
        ],
        [
            'user_id'=>null,
            'room_id'=>$rooms->id,
            'chair_id'=>7,
        ],
        [
            'user_id'=>null,

            'room_id'=>$rooms->id,
            'chair_id'=>8,
        ],
        [
            'user_id'=>$request->admin_id,
            'room_id'=>$rooms->id,
            'chair_id'=>9,
        ],
          [
            'user_id'=>null,

            'room_id'=>$rooms->id,
            'chair_id'=>10,
        ]
    ]);
    $join = Joinroom::create([
        'room_id'=>$rooms->id,
        'user_id'=>$request->admin_id,      
        ]);
    $Room=Rooms::where('id',$rooms->id)->with('joinRoom.user','admin','chairs.user')->first();
$Room->chatroom=[];
$Room->Token= $tokenagora;
    if($rooms){
        return $this->returnData('room',$Room);
        }else{
            return $this->returnError('E001', 'Can\'t add room');
     }  
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}

////******************************/ All Rooms
public function GetRooms($Categoty){
 
    try {
 if($Categoty=='Hot'){
    $Rooms=Rooms::where([['state',0],['fixed',0]])->orderBy('importance','DESC')->orderBy('user_number','DESC')->paginate(15);
    
}else{
    $Rooms=Rooms::where([['state',0],['Category',$Categoty],['fixed',0]])->orwhere([['state',0],['importance','!=',null],['fixed',0]])->orderBy('importance','DESC')->orderBy('user_number','DESC')->paginate(15);
}


    
   return $this->returnData('Rooms', $Rooms);
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
 public function ExploreRooms(){
 
    try {
   $Rooms=Rooms::where('state',0)->orderBy('user_number','DESC')->paginate(15);
   return $this->returnData('Rooms', $Rooms);
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
 
 public function FixedRooms(){
 
    try {

    $Rooms=Rooms::where([['state',0],['importance','!=',null]])->orderBy('importance','DESC')->get();
   
   return $this->returnData('Rooms', $Rooms);
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }


 public function GetNewRooms(){

    try {
      
   $Rooms=Rooms::where([['state',0],['user_number','!=',0]])->orderBy('importance','DESC')->orderBy('created_at','DESC')->paginate(15);
   return $this->returnData('Rooms', $Rooms );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }

 public function GetRoomWeb(){

    try {
      
   $Rooms=Rooms::where('state',0)->with('admin')->orderBy('Karisma','DESC')->paginate(15);
   return $this->returnData('Rooms', $Rooms );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }

 function  GetRecomended(){ 
    
    try {
      
     $Rooms= Rooms::where([['state', 0],['user_number','!=',0]])->inRandomOrder()->paginate(10);
     return $this->returnData('Rooms',$Rooms);

} catch (\Exception $ex) {
return $this->returnError($ex->getCode(), $ex->getMessage());
} 
   }

   function  GetRecomended2(){ 
    
    try {
      
     $Rooms= Rooms::where([['state', 0],['user_number','!=',0],['karisma','!=',0]])->orderBy('created_at','DESC')->inRandomOrder()->paginate(15);
     return $this->returnData('Rooms',$Rooms);

} catch (\Exception $ex) {
return $this->returnError($ex->getCode(), $ex->getMessage());
} 
   }

   function  GetCountryRooms($city){ 
    try {
     $Rooms= Rooms::where([['state', 0],['city',$city]])->orderBy('user_number','DESC')->paginate(15);
     return $this->returnData('Rooms',$Rooms);

} catch (\Exception $ex) {
return $this->returnError($ex->getCode(), $ex->getMessage());
} 
   }
//-------------------------------Close Room

 function SetThroneChair(Request $request){
   
        try {

            $rules = [
                'room_id' => 'required',
                'status' => 'required',
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Room=Rooms::where('id',$request->room_id)->update(array('SecondKing' => $request->status));
         
            if($Room){
                 event(new RoomEvent(18, $request->status , $request->room_id));
                
                return $this->returnData('SetThroneChair', $request->status);
            }else{
                return $this->returnError(123,'Cant Set ThroneChair');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
    
     function ChangeEffictStatus(Request $request){
   
        try {

            $rules = [
                'room_id' => 'required',
                'status' => 'required',
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Room=Rooms::where('id',$request->room_id)->update(array('stopeffect' => $request->status));
         
            if($Room){
                 event(new RoomEvent(31, $request->status , $request->room_id));
                
                return $this->returnData('SetThroneChair', $request->status);
            }else{
                return $this->returnError(123,'Cant Set ThroneChair');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
    
//  public function CloseRoom(Request $request){
//     try {
//         $rules = [
//         'room_id' => 'required|exists:rooms,id',
//         'admin_id' =>'required'
//         ];
//         $validator = Validator::make($request->all(), $rules);

//         if ($validator->fails()) {
//             $code = $this->returnCodeAccordingToInput($validator);
//             return $this->returnValidationError($code, $validator);
//         }
//   $Roomstate=tap(Rooms::where('id',$request->room_id))->update(['state'=>1])->first();
//   return $this->returnData('Roomstate', $Roomstate);
// } catch (\Exception $ex) {
//     return $this->returnError($ex->getCode(), $ex->getMessage());
// }
//  }
//---------------------------------Update Room
// public function UpdateRoom(Request $request){
  
//     try {
//         $rules = [
//             'room_id' => 'required|exists:rooms,id',
//             'image' => 'nullable|image|mimes:jpg,string,png,jpeg|max:3048',
//                  ]; 
//         $validator = Validator::make($request->all(), $rules);

//         if ($validator->fails()) {
//             $code = $this->returnCodeAccordingToInput($validator);
//             return $this->returnValidationError($code, $validator);
//         }
        
         
//  $userinfo=Rooms::find($request->room_id);
//  $user= UserApp::where('rememper_token',$request->header('Authorization'))->first();
//  if($user==null){
//      return '';
//  }else if($user->id!=$userinfo->admin_id){
//           return '';

//  }
 
//      if($request->hasfile('image')){
//                       $path=strstr( $userinfo->first()->image,"images");
//                       if(File::exists($path)){
//                      File::delete($path);
//                  }
//                 $fileName =time().'.png';   
//                 $file1 = $request->image->move(public_path('images'),$fileName);
//               $request->image=$fileName;

//  $userinfo->update(['image' =>  $request->image]);
//               }
               
//               if($request->hasfile('animateimage')){
//                 $path=strstr( $userinfo->first()->animateimage,"images");
//                  if(File::exists($path)){
//               File::delete($path);
//           }
//           $fileName =time().'1.png';   
//           $file1 = $request->animateimage->move(public_path('images'),$fileName);
//         $request->animateimage=$fileName;

// $userinfo->update(['animateimage' =>  $request->animateimage]);
//          }
//  $userinfo->update([ 
//  'name' =>  $request->name,
//  'animateimage' =>  $request->animateimage,
//  'Category'=>$request->Category,
//   'RoomAds'=>$request->RoomAds,
//  ]);
//     if($userinfo){ 
//         event(new RoomEvent(11, Rooms::find($request->room_id), $request->room_id));
//         return $this->returnData('room',Rooms::find($request->room_id));
//     }else{
//         return $this->returnError('162','cant update');
//     }

// } catch (\Exception $ex) {
//     return $this->returnError($ex->getCode(), $ex->getMessage());
// }
// }
public function UpdateRoomWeb(Request $request){
    try {
        $rules = [
            'room_id' => 'required|exists:rooms,id',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:3048',

                 ]; 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
 $userinfo=Rooms::find($request->room_id);
     if($request->hasfile('image')){
                      $path=strstr( $userinfo->first()->image,"images");
                       if(File::exists($path)){
                     File::delete($path);
                 }
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
              $request->image=$fileName;

 $userinfo->update(['image' =>  $request->image]);
               }
               
               if($request->hasfile('animateimage')){
                $path=strstr( $userinfo->first()->animateimage,"images");
                 if(File::exists($path)){
               File::delete($path);
           }
          $fileName =time().'1.png';   
          $file1 = $request->animateimage->move(public_path('images'),$fileName);
        $request->animateimage=$fileName;

$userinfo->update(['animateimage' =>  $request->animateimage]);
         }
 $userinfo->update([ 
 'name' =>  $request->name,
 'RoomAds'=>  $request->RoomAds,
 ]);
    if($userinfo){ 
        event(new RoomEvent(11, Rooms::find($request->room_id), $request->room_id));
        return $this->returnData('room',Rooms::where('id',$request->room_id)->with('admin')->first());
    }else{
        return $this->returnError('162','cant update');
    }

} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}
//---------------------------------SetPassword Room
public function SetPasswordRoom(Request $request){
    try {
        $rules = [
            'room_id' => 'required|exists:rooms,id',
            'password' => 'required',
    
                 ]; 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

    $room=tap(Rooms::find($request->room_id))->update(array(
        'password' => $request->password,
        'Locked'=>1
    ))->first();
    event(new RoomEvent(12, ["password"=>$request->password,
    "room_id"=>$request->room_id
], $request->room_id));
   return $this->returnData('room',Rooms::find($request->room_id));
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}
//-------------------------------------

public function RemovePasswordRoom(Request $request){
    try {
        $rules = [
            'room_id' => 'required|exists:rooms,id',
  
    
                 ]; 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

    $room=tap(Rooms::find($request->room_id))->update(array(
        'password' => null,
        'Locked'=>0
    ))->first();
    event(new RoomEvent(12, ["password"=>$request->password,"room_id"=>$request->room_id
], $request->room_id));
   return $this->returnData('room',Rooms::find($request->room_id));
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}
 //----------------------------------------
function  gettoken($uid,$channelName,$ROLE){
//$appId=10745abefd5e4b5fb21fe47c3d69cc7b;
//$appCertificate =35c73e507f664a9d8fab66b51f0e08e8;
    $appId = "08ab6ff57ac54a68a9e995c1a36aa055";
$appCertificate = "fe07afc2b9be4d13abf371609d3d30df";

$tokenExpirationInSeconds = 86400; 
$privilegeExpirationInSeconds = 86400; 
$token = RtcTokenBuilder2::buildTokenWithUid($appId, $appCertificate, $channelName, $uid,$ROLE  , $tokenExpirationInSeconds, $privilegeExpirationInSeconds);
return $token;
 
 }

}