<?php

namespace App\Http\Controllers;

use Validator;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\FollowRoom;

class FollowRoomController extends Controller
{
    
       use GeneralTrait;
 public function FollowRoom(Request $request){
            try {
            $rules = [
                "room_id" => "required",
                "user_id"=> "required",
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
          
         $FollowRoom = FollowRoom::create([
                'room_id'=> $request->room_id,
                'user_id'=> $request->user_id,
              
            ]);
            if($FollowRoom){
                return $this->returnData('FollowRoom',$FollowRoom);
                }else{
                    return $this->returnError('E001', 'Can\'t add FollowRoom');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }
 public function RemoveFollowRoom(Request $request){
            try {
            $rules = [
                "room_id" => "required",
                "user_id"=> "required",
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
          
         $FollowRoom = FollowRoom::where([['room_id',$request->room_id],['user_id',$request->user_id]])->delete();
            if($FollowRoom){
                return $this->returnData('FollowRoom',$FollowRoom);
                }else{
                    return $this->returnError('E001', 'Can\'t add FollowRoom');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }
 public function  GetFollowRoom($userid){

        try {
      
            $FollowRoom=FollowRoom::where('user_id',$userid)->with('room')->get();
            return $this->returnData('FollowRoom', $FollowRoom );
         } catch (\Exception $ex) {
             return $this->returnError($ex->getCode(), $ex->getMessage());
         }

    }

}
 