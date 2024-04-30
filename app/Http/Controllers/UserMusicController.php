<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\UserMusic;

class UserMusicController extends Controller
{
     use GeneralTrait;
    //-------------------------------------- 
        public function AddUserMusic(Request $request){
            try {
            $rules = [
                "name" => "required",
                "user_id"=> "required",
                "music"=>"required",
              
            ];
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $music='';
          
            if($request->hasfile('music')){
                $fileName =time().'.mp3';   
                $file1 = $request->music->move(public_path('images'),$fileName);
               $music=$fileName;
               } 
              
               $UserMusic = UserMusic::create([
                'name'=> $request->name,
                'user_id'=> $request->user_id,
                'url'=>   $music,
              
            ]);
            if($UserMusic){
                return $this->returnData('UserMusic',$UserMusic);
                }else{
                    return $this->returnError('E001', 'Can\'t add UserMusic');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }
  //-------------------------------------- 
  
  public function GetMyMusic($id){

    try {

   $UserMusic=UserMusic::where('user_id',$id)->get();
   return $this->returnData('UserMusic',   $UserMusic);
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }

}
