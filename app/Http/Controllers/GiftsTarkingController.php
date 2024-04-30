<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
 use DB;
use App\Models\Chairs;
use App\Models\GiftsTarking;
use Illuminate\Http\Request;
 use Carbon\Carbon;

use App\Models\UserApp;
class GiftsTarkingController extends Controller
{
    use GeneralTrait;
    public function AddGiftsTarking(Request $request){
       try {
       
     
      
           $GiftsTarking = GiftsTarking::create([
            'sender_id'=> 1,
            'reciver_id'=>1,
            'gift_id'=>1,
            'room_id'=>1,
            'karisma'=>100,
        ]);
        if($GiftsTarking){
            return $this->returnData('GiftsTarking',$GiftsTarking);
            }else{
                return $this->returnError('E001', 'Can\'t add GiftsTarking');
         }  
    } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
        
    }
 
     public function GetUserKarismaDetales($id,$Roomid){
         $supporter= GiftsTarking::where([['reciver_id',$id],['room_id',$Roomid]])->with('user')->select("sender_id",DB::raw('sum(karisma) as karisma') ) 
->groupBy("sender_id")->orderBy('karisma', 'DESC')->get();
return $supporter;
     }
     
       public function GetSuporterKarisma($id){
             
    
         $supporter= GiftsTarking::where('sender_id',$id )->with('reciuveuser:id,name')->select("reciver_id",DB::raw('sum(karisma) as karisma') ) 
->groupBy("reciver_id")->orderBy('karisma', 'DESC')->get();
return $supporter;
     }
     
       public function GetUserKarismaDetales2($id,$Roomid,$chairid){
           $chair=Chairs::where('id',$chairid)->first();
         
         
           $supporter= GiftsTarking::where([['reciver_id',$id],['room_id',$Roomid],['created_at','>=',$chair->joindate]])->with('user')->select("sender_id",DB::raw('sum(karisma) as karisma') ) 
->groupBy("sender_id")->orderBy('karisma', 'DESC')->get();
return $supporter;
     }
}
