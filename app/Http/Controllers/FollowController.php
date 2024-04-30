<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\follow;
use Validator;
use App\Models\Freinds;
use App\Traits\GeneralTrait;
use  App\Events\UserEvent;
use App\Models\UserApp;
class FollowController extends Controller
{
    use GeneralTrait;
    public function Followuser(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "sender_id" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        
    $checkfollow=follow::where([['sender_id',$request->user_id],['user_id',$request->sender_id]])->first();
    $sender=UserApp::find($request->sender_id);
    if($checkfollow!=null){
         $Freinds = Freinds::create([
                'user_id'=>$request->user_id,
                'sener_id'=>$request->sender_id,
                'state'=>'0'
            ]);
    }
    
           $Follow = follow::create([
            'user_id'=> $request->user_id,
            'sender_id'=> $request->sender_id,
            
           ]);
        if($Follow){ 
            
              event(new UserEvent(14,['sender'=>$sender],$request->user_id));
            return $this->returnData('Follow',$Follow);
            }else{
                return $this->returnError('E001', 'Can\'t add Follow');
             }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
 
    public function RemoveFollowRoom(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "sender_id" => "required",
        
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $Follow=follow::where([['sender_id',$request->sender_id],['user_id',$request->user_id]])->orwhere([['sender_id',$request->user_id],['user_id',$request->sender_id]])->first();
 
if($Follow ->status==1){
    $Follow ->status=0;
    $Follow ->save();
}else if($Follow->sender_id== $request->sender_id){

    $Follow ->delete();
} 
      
           if($Follow){ 
            return $this->returnData('Follow',$Follow);
            }else{
                return $this->returnError('E001', 'Can\'t remove Follow');
             }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    public function RemoveFollow(Request $request){
        try {
        $rules = [
            "follow_id" => "required",

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        

           $Follow = follow ::where('id',$request->follow_id)->first();
               $frinds=Freinds::where([['sener_id',$Follow->user_id],['user_id',$Follow->sender_id]])->orwhere([['sener_id',$Follow->sender_id],['user_id',$Follow->user_id]])->first();
               if( $frinds!=null){
                        $frinds ->delete();
               }
           $Follow ->delete();
      
           if($Follow){ 
            return $this->returnData('Follow',$Follow);
            }else{
                return $this->returnError('E001', 'Can\'t remove Follow');
             }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function RemoveUserFollow(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "sender_id" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        
           $Follow = follow ::where([['sender_id',$request->sender_id],['user_id',$request->user_id]])->first();
    
           $Follow ->delete();
      
           if($Follow){ 
            return $this->returnData('Follow',$Follow);
            }else{
                return $this->returnError('E001', 'Can\'t remove Follow');
             }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function ReturnFollow(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "sender_id" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    
            $Follow2 =follow ::where([['sender_id',$request->user_id],['user_id',$request->sender_id]])->first();
                $Freinds = Freinds::create([
                'user_id'=>$request->user_id,
                'sener_id'=>$request->sender_id,
                'state'=>'0'
            ]);
            if($Follow2!=null){
                return 'ALREADY FOLLOW';
            }else{
                
           $Follow = follow::create([
            'user_id'=> $request->sender_id,
            'sender_id'=> $request->user_id,
            
           ]);
            }
    
       
       
           if($Follow||$Follow2){ 
            return $this->returnData('Follow',$Follow);
            }else{
                return $this->returnError('E001', 'Can\'t add Follow');
             }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    //----------------------------------Getmyfollowers

    public function Getmyfollowers($id){
        try {
    $followers=follow::where('user_id',$id)->with('user','otheruser')->orderBy('created_at', 'DESC')->get();
    return $this->returnData('Follow',$followers);
          
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

      //----------------------------------Getmyfollowing

      public function Getmyfollowing($id){
        try { 
    $followers=follow::where('sender_id',$id)->with('user','otheruser')->orderBy('created_at', 'DESC')->get();
    return $this->returnData('Follow',$followers);
          
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//---------------------------------------------Frinds

public function Getmyfrinds($id){
    try {
$followers=follow::where([['sender_id',$id],['status',1]])->orwhere([['user_id',$id],['status',1]])->with('user','otheruser')->get();
return $this->returnData('Follow',$followers);
      
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}

}
