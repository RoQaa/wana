<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Traits\GeneralTrait;
use App\Models\ShopCategory;
class ShopCategoryController extends Controller
{

    use GeneralTrait;
    //--------------------------------------AddCategory
    public function AddShopCategory(Request $request){
        try {
        $rules = [
            "name" => "required|unique:shop_categories,name",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
           $ShopCategory = ShopCategory::create([
            'name'=> $request->name,
        ]);
        if($ShopCategory){
            return $this->returnData('ShopCategory',$ShopCategory);
            }else{
                return $this->returnError('E001', 'Can\'t add ShopCategory');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//--------------------------------------RemoveCategory
    public function RemoveShopCategory(Request $request){
        try {
        $rules = [
            "id" => "required|exists:shop_categories,id",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
 
        $ShopCategory=ShopCategory::where('id',$request->id)->delete();

        if($ShopCategory){
            return $this->returnData('ShopCategory',$ShopCategory);
            }else{
                return $this->returnError('E001', 'Can\'t remove ShopCategory');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//--------------------------------------UpdateCategory
public function UpdateShopCategory(Request $request){
    try {
    $rules = [
        "id" => "required|exists:shop_categories,id",
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
 

    
    $ShopCategory=tap(ShopCategory::find($request->id))->update($request->all())->first();

    if($ShopCategory){
        return $this->returnData('ShopCategory',ShopCategory::find($request->id));
        }else{
            return $this->returnError('E001', 'Can\'t update ShopCategory');
     }  
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}


//----------------------------------------  GetShopCategory
public function GetShopCategory(){
    $ShopCategory=ShopCategory::where('status',1)->with('items')->get();
    return $this->returnData('ShopCategory',$ShopCategory);
}

private $ids=11;
public function GetuserShopCategory($id){
    $this->ids=$id;
    $ShopCategory=ShopCategory::where([['status',1],['cp',0]])->with(['sales'=> function ($myWithQuery) {
        $myWithQuery->where([['user_id', $this->ids],['status',1]])->with('item'); 
     }])->get();
    return $this->returnData('MyitemsCategory',$ShopCategory);
}

}
