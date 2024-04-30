<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\JoinAgency;
use App\Models\UserApp;
use App\Models\Joinroom;
use Carbon\Carbon;
use DateTime;

class JoinAgencyController extends Controller
{
    use GeneralTrait;
    public function JoinAgency(Request $request){
   
       try {
       $rules = [
           "user_id" => "required|exists:user_apps,id|unique:join_agencies,user_id,$request->user_id,agancy_id",
           "agancy_id" => "required|exists:agencies,id",
       ];
       $validator = Validator::make($request->all(), $rules);
   
       if ($validator->fails()) {
           $code = $this->returnCodeAccordingToInput($validator);
           return $this->returnValidationError($code, $validator);
       }
           $join = JoinAgency::create([
           'user_id'=>$request->user_id,
           'agancy_id'=>$request->agancy_id,       
             ]);
       if($join){
           return $this->returnData('join',$join);
           }else{
               return $this->returnError('E001', 'Can\'t join this Agency');
        }  
       } catch (\Exception $ex) {
           return $this->returnError($ex->getCode(), $ex->getMessage());
       }
   }

//----------------------------------------

public function GetJoinAgency($id,$UserId){

  try {
      
   $Agency=UserApp::where('AgencyId',$id)->with('myvip.vip')->paginate(50);
   
   $asd=$this->GetJoinTime($UserId);
   return $this->returnData('Agency', ['Members'=>$Agency,'menuit'=> $asd ]);
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
 
 
           public function GetJoinTime($id){
   $minitsuser=0;
            try {
     
           $Joinroom=Joinroom::where('user_id',$id)->get();
           foreach ($Joinroom as $user) {
               
                $minitsuser=    $minitsuser + $this->getDifference($user->created_at,$user->updated_at);
           }
    
$hours = floor($minitsuser / 60);
$min = $minitsuser - ($hours * 60);
 
    return  $minitsuser;
         
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
         }
         
         public function getDifference($created_at, $updated_at) {
 
    $minutes = $created_at->diffInMinutes($updated_at);
   
    return  $minutes;
}
 
   
}
