<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Roomimages;
class RoomimagesController extends Controller
{
         use GeneralTrait;
            public function AddRoomimages(Request $request){
            try {
            $rules = [
            
                "user_id"=> "required",
             'image' => 'required|image|mimes:jpg,png,jpeg|max:3048',
              
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
              
               $Roomimages = Roomimages::create([
              
                'user_id'=> $request->user_id,
                'image'=> $image,
              
            ]);
            if($Roomimages){
                return $this->returnData('Roomimages',$Roomimages);
                }else{
                    return $this->returnError('E001', 'Can\'t add Roomimages');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }
    
}
