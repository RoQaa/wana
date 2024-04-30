<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\agencypayments;
use App\Traits\GeneralTrait;
use Validator;
use App\Models\Agency;
use  App\Events\UserEvent;
use App\Models\UserApp;
class AgencypaymentsController extends Controller
{

    use GeneralTrait;
    public function ChargeAgencyPayments(Request $request){
        try {
        $rules = [
            "agency_id" =>  "required",
            "user_id"=> "required",
            'cost'=> "required",
            'coins'=> "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
      
        $Agency=Agency::where('id',$request->agency_id)->first();
        if($Agency->coins<$request->coins){
            return $this->returnError('E001', 'Can\'t charge');
        }
        $user=UserApp::where('myappid',$request->user_id)->orwhere('Newid',$request->user_id)->first();
        $user->increment('coins', $request->coins);
        $Agencypayment = agencypayments::create([
            'agency_id'=> $request->agency_id,
            'user_id'=>    $user->id,
            'cost'=>  $request->cost,
            'coins'=>  $request->coins,
             
        ]);
        
        $Agency->decrement('coins', $request->coins);
       $Agency=agencypayments::where('id',$Agencypayment->id)->with('user')->first();
        if($Agency){
            event(new UserEvent(6,['coins'=>$request->coins],$user->id));
            return $this->returnData('Agency',$Agency);
            }else{
                return $this->returnError('E001', 'Can\'t add Agency');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    
    public function totalsum($id){
     $asd=   agencypayments::where('agency_id',$id)->get()->sum('coins');
        return    $asd;
    }



}
