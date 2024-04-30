<?php

namespace App\Http\Controllers;
use App\Models\Chairs;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use  App\Events\RoomEvent;

use Validator;
class ChairsController extends Controller
{
    use GeneralTrait; 
    public function LockChair(Request $request){
        try {
            $rules = [
            'chair_id' => 'required',
            'room_id' => 'required',
            'Lock'=> 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
 
            if ($validator->fails()) {
            
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $Chairs=tap(Chairs::where('id',$request->chair_id))->update(['Lock'=>$request->Lock])->first();
        
 if($Chairs){
     event(new RoomEvent(9, ['chair'=>$Chairs,'state'=>$request->Lock],$request->room_id));
 }  

            return $this->returnData('Chairs',['chair'=>$Chairs,'state'=>$request->Lock]);
           
    } catch (\Exception $ex) {
        return 'asda';
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }

}
