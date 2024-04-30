<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Validator;
use Auth;
use App\Models\AgencyLeaverequest;
use App\Models\UserApp;
class AgencyLeaverequestController extends Controller
{    use GeneralTrait;
    public function LeaveAgency(Request $request){
        try {
            $rules = [
                "user_id" => "required",
                "agancy_id" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $user=UserApp::where('id', $request->user_id)->first();
          
           $AgencyLeaverequest = AgencyLeaverequest::create([
            "user_id"=>  $request->user_id,
            "agancy_id"=>  $request->agancy_id,
            'Karisma'=>  $user->AgencyKarisma,
    
        ]);
        $user->AgencyId=null;
        $user->AgencyKarisma=0;
        $user->save();
        if($AgencyLeaverequest){
            return $this->returnData('AgencyLeaverequest',$AgencyLeaverequest);
            }else{
                return $this->returnError('E001', 'Can\'t add AgencyLeaverequest');
         }  
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
