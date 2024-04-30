<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\roomcategory;
use App\Traits\GeneralTrait;
use Validator;
class RoomcategoryController extends Controller
{

    use GeneralTrait;

    public function AddRoomCategory(Request $request){
        try {
        $rules = [
            "name" => "required|unique:roomcategories,name",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
     
           $roomcategory = roomcategory::create([
            'name'=> $request->name,
        ]);
        if($roomcategory){
            return $this->returnData('roomcategory',$roomcategory);
            }else{
                return $this->returnError('E001', 'Can\'t add roomcategory');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
}
