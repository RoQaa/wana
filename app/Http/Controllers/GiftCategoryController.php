<?php

namespace App\Http\Controllers;
use Validator;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\GiftCategory;
class GiftCategoryController extends Controller
{ 
       use GeneralTrait; 
//--------------------------------------AddCategory
    public function AddCategory(Request $request){
        try {
        $rules = [
            "name" => "required|unique:gift_categories,name",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
           $category = GiftCategory::create([
            'name'=> $request->name,
        ]);
        if($category){
            return $this->returnData('category',$category);
            }else{
                return $this->returnError('E001', 'Can\'t add category');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//--------------------------------------RemoveCategory
    public function RemoveCategory(Request $request){
        try {
        $rules = [
            "id" => "required|exists:gift_categories,id",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
        $category=GiftCategory::where('id',$request->id)->delete();

        if($category){
            return $this->returnData('category',$category);
            }else{
                return $this->returnError('E001', 'Can\'t remove category');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//--------------------------------------UpdateCategory
public function UpdateCategory(Request $request){
    try {
    $rules = [
        "id" => "required|exists:gift_categories,id",
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
 

    
    $category=tap(GiftCategory::find($request->id))->update($request->all())->first();

    if($category){
        return $this->returnData('category',GiftCategory::find($request->id));
        }else{
            return $this->returnError('E001', 'Can\'t update category');
     }  
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}
//--------------------------------------GetCategory


public function GetCategory(){
    try {
        $catigoris=GiftCategory::where('status','!=',5)->orderBy('sorting', 'DESC')->with('gifts:category_id,name,id,image,price,status,sound')->get(['id','name']);
 
return $this->returnData('category',$catigoris);

    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}
}
