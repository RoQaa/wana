<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Supervisors;
use  App\Events\RoomEvent;
class SupervisorsController extends Controller
{
    use GeneralTrait;
    //------------
    public function AddSupervisors(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "room_id"=> "required",
         
         ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
      
    
           $Supervisors = Supervisors::create([
            'user_id'=> $request->user_id,
            'room_id'=> $request->room_id, 
           
        ]);
        if($Supervisors){
            event(new RoomEvent(14, $request->user_id, $request->room_id));

            return $this->returnData('Supervisors',$Supervisors);
            }else{
                return $this->returnError('E001', 'Can\'t add  Supervisors');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function RemoveSupervisors(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "room_id"=> "required",
        
         ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
      
    
           $Supervisors = Supervisors::where([['user_id',$request->user_id],['room_id',$request->room_id]])->first()->delete();
        if($Supervisors){
            event(new RoomEvent(15, $request->user_id, $request->room_id));
            return $this->returnData('Supervisors',$Supervisors);
            }else{
                return $this->returnError('E001', 'Can\'t add  Supervisors');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
   
    public function GetRoomSupervisors($id){
        try {
           $Supervisors = Supervisors::where('room_id',$id)->with('user')->get();
           return  $Supervisors;
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
