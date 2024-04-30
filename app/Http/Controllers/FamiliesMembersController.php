<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserApp;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Families;
use App\Models\Families_Members;
use App\Models\Rooms;
use App\Models\Leaderboard;
class FamiliesMembersController extends Controller
{
       use GeneralTrait;
      public function joinFamily(Request $request){
  
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
        $user=UserApp::find($request->user_id);
        if($user->FamilyId!=null){
            return 'error';
        }
       
        $family=Families::find($request->Family_id);  
                // $user->update([ 'FamilyModel'=>$family,'FamilyId'=>$request->Family_id]);
$familymember=Families_Members:: create([
           'user_id'=>$request->user_id,
           'Family_id'=>$request->Family_id,
        ]);
       
        if( $familymember){
            return $this->returnData('Families', $familymember);
            }else{
                return $this->returnError('E001', 'Can\'t add Families');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
      public function Canclejoin(Request $request){
  
        try {
        $rules = [
          
            "user_id" => "required",
            "family_id" => "required",
          
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
      
        $family=Families_Members::where([['user_id',$request->user_id],['Family_id',$request->family_id]])->first() ;  
        if( $family->status==1){
              return $this->returnError('E001', 'Error');
        }
            $family->delete();   
 
        if(  $family){
            return $this->returnData('Families', $family);
            }else{
                return $this->returnError('E001', 'Can\'t add Families');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

  

      public function Acceptjoin(Request $request){
  
        try {
        $rules = [
          
            "join_id" => "required",
            
          
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
      
        $requesthoin=Families_Members::find($request->join_id) ; 
        $Allrquests=Families_Members::where([['id','!=',$request->join_id],['user_id',$requesthoin->user_id]])->delete();
        
   
       $requesthoin->update(['status'=>1]);
       
        $family=Families::find( $requesthoin->Family_id);
        $family->increment('nubmers', 1);  
        $user=UserApp::find($requesthoin->user_id)->update(['FamilyId'=>$family->id]);
       
         // $user->update([ 'FamilyModel'=>$family,'FamilyId'=>$request->Family_id]);
 
        if( $requesthoin){
            return $this->returnData('Families', $requesthoin);
            }else{
                return $this->returnError('E001', 'Can\'t add Families');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function LeaveFamily(Request $request){
  
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
      
        $requesthoin=Families_Members::where([['user_id',$request->user_id],['Family_id',$request->Family_id]])->delete();
       $rooms=Rooms::where('FamilyId',$request->Family_id)->update(['FamilyId'=>null]);
       $leaderBoard=Leaderboard::where([['user_id',$request->user_id],['family_id',$request->Family_id]])->delete();
        $family=Families::find( $request->Family_id);
        $user=UserApp::find($request->user_id)->update(['FamilyModel'=>null,'FamilyId'=>null]);
 
 
        if(  $requesthoin){
            return $this->returnData('Families',  $requesthoin);
            }else{
                return $this->returnError('E001', 'Can\'t add Families');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    
        public function GetRequestFamily($id){

    try {

   $Families=Families_Members::where([['Family_id',$id],['status',0]])->with('user')->get();
   
   return $this->returnData('Families',$Families );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
}
