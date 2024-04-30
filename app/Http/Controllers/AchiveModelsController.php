<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Validator;
use App\Models\Joinroom;
use Carbon\Carbon;
use DateTime;

use App\Models\AchiveModels;
class AchiveModelsController extends Controller
{
    use GeneralTrait;
         public function AddAchiveModels(Request $request){
            try {
            $rules = [
                "image" => "required|unique:agencies,name",
                "blackimage"=> "required",
                'name'=> "required",
                'reason'=> "required",

            ];
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $image='';
            $blackimage='';
            if($request->hasfile('image')){
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
                $image=$fileName;
               } 
               if($request->hasfile('blackimage')){
                $fileName =time().'0.png';   
                $file1 = $request->blackimage->move(public_path('images'),$fileName);
                $blackimage=$fileName;
               } 
                $models = AchiveModels::create([
                'name'=> $request->name,
                'reason'=>  $request->reason,
                'image'=> $image,
                'blackimage'=> $blackimage,
           
            ]);
             
            if($models){
                return $this->returnData('models',$models);
                }else{
                    return $this->returnError('E001', 'Can\'t add models');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }

        public function GetModels(){

            try {
        
           $Models=AchiveModels::where('status',1)->get();
           return $this->returnData('Models', $Models );
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
 
    return     $hours  . ':' . $min ;
           $interval = $end->diff( $start);
           return $interval;
           return $this->returnData('Joinroom', $Joinroom );
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
         }
         
         public function getDifference($created_at, $updated_at) {
 
    $minutes = $created_at->diffInMinutes($updated_at);
   
    return  $minutes;
}
         
}
