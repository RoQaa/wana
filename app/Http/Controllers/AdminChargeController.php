<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\AdminCharge;
use Validator;
use App\Models\Agency;
use App\Traits\GeneralTrait;
use App\Models\UserApp;
use  App\Events\UserEvent;
class AdminChargeController extends Controller
{
    use GeneralTrait;
    public function ChargeUser(Request $request){
  
        try {
        $rules = [
            "user_id" => "required",
            "admin" => "required",
            "cost"=> "required",
            "coins"=> "required",
            "reason"=> "required",
          
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $user=UserApp::where('myappid',$request->user_id)->orwhere('Newid',$request->user_id)->first();
       
      
        $charge=AdminCharge:: create([
           'user_id'=> $user->id,
           'admin'=>$request->admin,
           'cost'=>$request->cost,
           'coins'=>$request->coins,
           'reason'=>$request->reason
        ]);
           event(new UserEvent(6,['coins'=>$request->coins],$user->id));
        $charge=AdminCharge::where('id', $charge->id)->with('Admin',"user")->first();

        if( $charge){
             $user->increment('coins',$request->coins);
            return $this->returnData('charge', $charge);
            }else{
                return $this->returnError('E001', 'Can\'t add charge');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function ChargeAgency(Request $request){
  
        try {
        $rules = [
            "agency_id" => "required",
            "admin" => "required",
            "cost"=> "required",
            "coins"=> "required",
            "reason"=> "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $agency=Agency::find($request->agency_id)->increment('coins',$request->coins);
    
        $charge=AdminCharge:: create([
           'agency_id'=>$request->agency_id,
           'admin'=>$request->admin,
           'cost'=>$request->cost,
           'coins'=>$request->coins,
             'reason'=>$request->reason
        ]);
        $charge=AdminCharge::where('id', $charge->id)->with('Admin',"Agency")->first();

        if( $charge){
            return $this->returnData('charge', $charge);
            }else{
                return $this->returnError('E001', 'Can\'t add charge');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function GetAgencyCharges(){

        try {
    
       $Agency=AdminCharge::where('agency_id','!=',null)->with('Admin',"Agency")->orderBy('created_at','DESC')->paginate(10);
       return $this->returnData('Agency', $Agency );
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }
     public function GetUserCharges(){

        try {
    
       $Agency=AdminCharge::where('user_id','!=',null)->with('Admin',"user")->orderBy('created_at','DESC')->paginate(50);
       return $this->returnData('Agency', $Agency );
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }
}
