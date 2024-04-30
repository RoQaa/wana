<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Traits\GeneralTrait;
use App\Models\ShippingPackage;
class ShippingPackageController extends Controller
{
    use GeneralTrait;
    public function AddShippingPackage(Request $request){
        try {
        $rules = [

            "cost"=>"required",
            "coins"=>"required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
           $ShippingPackage = ShippingPackage::create([
            'cost'=> $request->cost,
            'coins'=> $request->coins,
        ]);
        if($ShippingPackage){
            return $this->returnData('ShippingPackage',$ShippingPackage);
            }else{
                return $this->returnError('E001', 'Can\'t add ShippingPackage');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    //------------------------------------------------update
  
public function UpdateShippingPackage(Request $request){
    try {
    $rules = [
        "id" => "required|exists:shipping_packages,id",
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
 
    $image='';
            if($request->hasfile('image')){
                     $icon= $userinfo->first()->image;
                      $path=strstr( $icon,"images");
                       if(File::exists($path)){
                     File::delete($path);
                 }
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
                $image=asset('images/'.$fileName);
                $states=tap( ShippingPackage::find($request->id))->update(['image'=>$image]) ->first()->image;
                return $this->returnData('image', $states);
               }
    
    $ShopCategory=tap(ShippingPackage::find($request->id))->update($request->all())->first();

    if($ShopCategory){
        return $this->returnData('ShopCategory',ShippingPackage::find($request->id));
        }else{
            return $this->returnError('E001', 'Can\'t update ShopCategory');
     }  
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}

//------------------------------------------------remove
public function RemoveShippingPackage(Request $request){
    try {
    $rules = [
        "id" => "required|exists:shipping_packages,id",
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
 

    $ShippingPackage=ShippingPackage::where('id',$request->id)->delete();

    if($ShippingPackage){
        return $this->returnData('ShippingPackage',$ShippingPackage);
        }else{
            return $this->returnError('E001', 'Can\'t remove ShippingPackage');
     }  
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}
}
