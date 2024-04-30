<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Kickedusers;
class KickedusersController extends Controller
{        use GeneralTrait;
    public function kickuser(Request $request){

        try {
        $rules = [
            "user_id" => "required|exists:user_apps,id|unique:kickedusers,user_id,$request->user_id,room_id",
            "room_id" => "required|exists:rooms,id",
            "reason" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
           $kick = Kickedusers::create([
            'user_id'=> $request->user_id,
            'room_id'=>$request->room_id,
            'reason'=>$request->reason,   
        ]);
        if($kick){
            return $this->returnData('kick',$kick);
            }else{
                return $this->returnError('E001', 'Can\'t kick This User');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function unkickuser(Request $request){
        try {
        $rules = [
            "kick_id" => "required",
             
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
           $kick = tap(Kickedusers::where('id',$request->kick_id))->update(['state'=>1])->first();
        if($kick){
            return $this->returnData('kick',$kick);
            }else{
                return $this->returnError('E001', 'Can\'t kick This User');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }



    public function getkickeduser($room_id){

       $kickeduser = Kickedusers::where([['room_id',$room_id],['state','0']])->with('user')->get();
        return $kickeduser;
    }
}
