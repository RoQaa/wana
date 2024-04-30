<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserApp;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Families;
use App\Models\Leaderboard;
use App\Models\Rooms;
use App\Models\Families_Members;
use DB;
class FamiliesController extends Controller
{
    
        use GeneralTrait;
    public function CreateFamily(Request $request){
  
        try {
        $rules = [
            "user_id" => "required",
          
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $user=UserApp::find($request->user_id);
     
      if($user->coins<50000){
        return 'Error Acour';
        }
        $user->decrement('coins', 50000); 
    
       $image='';
        if($request->hasfile('image')){
            $fileName =time().'.png';   
            $file1 = $request->image->move(public_path('images'),$fileName);
            $image=$fileName;
           }
          
              
           $Families = Families::create([
           'name'=>$request->name,
           'describtion'=>$request->describtion,
           'image'=>$image,
           'user_id'=>$request->user_id,
           'Familyid'=>(string) mt_rand(1000000, 9999999),
             
        ]);
            $user->update(['FamilyAdmin'=>1,'FamilyId'=>$Families->id]);
            $Families=Families::where('id', $Families ->id)->with('user','members')->first();
        if($Families){
            return $this->returnData('Families',$Families);
            }else{
                return $this->returnError('E001', 'Can\'t add Families');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
   public function EditFamilyyName($id,$name){

    try {

   $Agency=Families::where('id',$id)->first();
 UserApp::where('id',$Agency->user_id)->decrement('coins', 5000);
   $Agency->update(['name'=>$name]);
   if($Agency){
 
      return $this->returnData('Agency', $Agency );
    }else{
      return $this->returnError('E001', 'Not Found');

    }
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}}
      public function GetAllFamily(){

    try {

   $Families=Families::where('status',1)->with('user','members','admins')->orderBy('Karisma','DESC')->get();
   
   return $this->returnData('Families',$Families );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
    public function GetFamilyRooms($id){

    try {

   $FamiliesRooms=Rooms::where([['FamilyId',$id],['state',0]])->orderBy('Karisma','DESC')->get();
   
   return $this->returnData('FamiliesRooms',$FamiliesRooms );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
        public function GetFamilyMembers($id){

    try {

   $users=UserApp::where('FamilyId',$id)->orderBy('FamilyKarisma','DESC')->get();
   
   return $this->returnData('users',$users );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
     public function LeaveFamily(Request $request){
  
        try {
        $rules = [
            "user_id" => "required",
            
          
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $user=UserApp::find($request->user_id);
       
        $family=Families::find($user->FamilyId)->decrement('nubmers', 1);  
                 $user->update([ 'FamilyModel'=>null,'FamilyId'=>null]);

       
        if( $family){
            return $this->returnData('Families', $family);
            }else{
                return $this->returnError('E001', 'Can\'t add Families');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
     
    public function GetFamilyProfile($id){
        $TopStar = Leaderboard::where([['family_id',$id],['status','4']])->with('user')->select("user_id",DB::raw('sum(coins) as coins') ) 
        ->groupBy("user_id")->orderBy('coins', 'DESC')->take(10)->get();
        $Members=UserApp::where('FamilyId',$id)->count();
        $Family=Families::where('id',$id)->with('Rooms')->first();
        $Family->TopStar=$TopStar;
        $Family->nubmers=$Members==0?1:$Members;
        try {
            return $this->returnData('Family',  $Family );
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
}


public function SearchFAmily($tittle){
    $users = Families::where('name','like','%'.$tittle.'%')->orwhere('Familyid','like','%'.$tittle.'%')->orderBy('Karisma','DESC')->take(20)->get();
    return $this->returnData('Family', $users );
  }

}