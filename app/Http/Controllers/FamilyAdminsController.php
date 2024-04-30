<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Traits\GeneralTrait;
 
use Validator;
use App\Models\FamilyAdmins;
class FamilyAdminsController extends Controller
{
     
     use GeneralTrait;
        public function AddAdmins(Request $request){
  
        try {
        $rules = [
            "user_id" => "required",
            "Family_id" => "required",
            
          
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
       
        
                // $user->update([ 'FamilyModel'=>$family,'FamilyId'=>$request->Family_id]);
$FamilyAdmins=FamilyAdmins:: create([
           'user_id'=>$request->user_id,
           'Family_id'=>$request->Family_id,
        ]);
       
        if( $FamilyAdmins){
            return $this->returnData('FamilyAdmins',$FamilyAdmins);
            }else{
                return $this->returnError('E001', 'Can\'t add FamilyAdmins');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
     public function RemoveAdmin(Request $request){
         FamilyAdmins::where([['user_id',$request->user_id],['Family_id',$request->Family_id]])->delete();
     }
     
}
