<?php

namespace App\Http\Controllers;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Validator;
use App\Models\RoomGifts;
use App\Models\gift;
class RoomGiftsController extends Controller
{    
      use GeneralTrait;

    public function sendgifts(Request $request){
  
        try {
        $rules = [
            "user_id" => "required|exists:user_apps,id",
            "gift_id" => "required|exists:gifts,id",
            "room_id" => "required|exists:rooms,id",
            "quantity"=> "required"
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
           $RoomGifts = RoomGifts::create([
            'user_id'=> $request->user_id,
            'gift_id'=> $request->gift_id,
            'room_id'=> $request->room_id,
            'quantity'=> $request->quantity,
        ]);
        if($RoomGifts){
            return $this->returnData('RoomGifts',$RoomGifts);
            }else{
                return $this->returnError('E001', 'Can\'t add RoomGifts');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
     public function getsumgifts($id){
         $sum=0;
         $RoomGifts=RoomGifts::where([['user_id',$id],['state',0]])->get();
       foreach ($RoomGifts as $sku) {
       
           $gift=gift::where('id',$sku->gift_id)->first();
           $sum=$sum+( $gift->price*$sku->quantity);
    // Code Here
}
return $sum;
         return $RoomGifts;
         
     }
}
