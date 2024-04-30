<?php

namespace App\Http\Controllers;

 
use Validator;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\UserModels;

class UserModelsController extends Controller
{
         use GeneralTrait;
    //-------------------------------------- 
        public function AddUserModel(Request $request){
            try {
            $rules = [
          
                "user_id"=> "required",
                "image"=>"required",
              
            ];
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $image='';
          
            if($request->hasfile('image')){
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
                $image=$fileName;
               } 
              
               $UserModels = UserModels::create([
               
                'user_id'=> $request->user_id,
                'image'=>   $image,
              
            ]);
            if($UserModels){
                return $this->returnData('UserModels',$UserModels );
                }else{
                    return $this->returnError('E001', 'Can\'t add UserModels');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }
}
