<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Redis;

use App\Models\ShopItem;
class ShopItemController extends Controller
{  
      use GeneralTrait;
    public function Addshopitem(Request $request){
        try {
        $rules = [
            "name" => "required",
            "svggift" => "required",
            "imagegift" => "required",
            "shopcategory_id" => "required|exists:shop_categories,id",
            "price" => "required",
            "kind"=> "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
        $image='';
        $svgimage='';
        if($request->hasfile('imagegift')){
            $fileName =time().'.png';   
            $file1 = $request->imagegift->move(public_path('images'),$fileName);
            $image=$fileName;
           } 
           if($request->hasfile('svggift')){
            $fileName =time().'.svg';   
            $file1 = $request->svggift->move(public_path('images'),$fileName);
            $svgimage=$fileName;
           }
           $shopitem = ShopItem::create([
            'name'=> $request->name,
            'imagegift'=>$image,
            'svggift'=>$svgimage,
            'shopcategory_id'=>$request->shopcategory_id,
            'price'=>$request->price,'kind'=>$request->kind
        ]);
       // Redis::del('item');
        if($shopitem){
            return $this->returnData('shopitem',$shopitem);
            }else{
                return $this->returnError('E001', 'Can\'t add shopitem');
         }  
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    
     public function RePriceshopitem(){
         $ShopItems=ShopItem::all();
        foreach ( $ShopItems as $items) {
            $items->price= (int)$items->price/7;
            $items->save();
        }
     }
    
//--------------------------------------Removeshopitem
public function Removeshopitem(Request $request){
    try {
    $rules = [
        "id" => "required|exists:shop_items,id",
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
 

    $ShopItem=ShopItem::where('id',$request->id)->delete();

    if($ShopItem){
        return $this->returnData('ShopItem',$ShopItem);
        }else{
            return $this->returnError('E001', 'Can\'t remove ShopItem');
     }  
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}
//--------------------------------------Updateshopitem
public function UpdateShopItem(Request $request){
try {
$rules = [
    "id" => "required|exists:shop_items,id",
];
$validator = Validator::make($request->all(), $rules);

if ($validator->fails()) {
    $code = $this->returnCodeAccordingToInput($validator);
    return $this->returnValidationError($code, $validator);
}

$image='';
$svgimage='';
$ShopItem=ShopItem::find($request->id);
if($request->hasfile('imagegift')){
    $fileName =time().'.png';   
    $file1 = $request->imagegift->move(public_path('images'),$fileName);
    $ShopItem->imagegift=$fileName;
  
    $ShopItem->save();
 
   } 
   if($request->hasfile('svggift')){
    $fileName =time().'.svg';   
    $file1 = $request->svggift->move(public_path('images'),$fileName);
    $ShopItem->svggift= $fileName;
    $ShopItem->save();
   }

 $gift=tap(ShopItem::find($request->id))->update($request->except(['svggift', 'imagegift']))->first();

if($gift){
    return $this->returnData('ShopItem',ShopItem::find($request->id));
    }else{
        return $this->returnError('E001', 'Can\'t update ShopItem');
 }  
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}
//--------------------------------------Allitems
public function allitem(){
    $cachedShopItem = Redis::get('item');
    if(isset($cachedShopItem)){
        $items = json_decode($cachedShopItem, FALSE);
        return $items;
    }
    $ShopItem=ShopItem::all();
    Redis::set('item',$ShopItem);
    Redis::expire('item',10);
    return $ShopItem;
}
//--------------------------------------
public function items($id){
    $cachedShopItem = Redis::get('item');
    if(isset($cachedShopItem)){
        $items = json_decode($cachedShopItem, FALSE);
        return $items;
    }
    $ShopItem=ShopItem::where('shopcategory_id',$id)->get();
    Redis::set('item',$ShopItem);
    Redis::expire('item',10);
    return $ShopItem;

}
}
