<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Validator;
use App\Models\Sales;
use App\Models\ShopItem;
use Carbon\Carbon;
use DB;
use App\Models\UserApp;
class SalesController extends Controller
{
    use GeneralTrait;
    public function byeitem(Request $request){
        try {
        $rules = [
            "item_id" => "required|exists:shop_items,id",
            "user_id" => "required|exists:user_apps,id",
            "day"=>"required",
            "price"=>"required",
            "category_id"=>"required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
         $user= UserApp::where('id', $request->user_id)->first();
        if($user->coins<$request->price){
            return $this->returnError('E011', 'Can\'t add Sales');
        }
        if( $user->coins<$request->price){
            return 'asd';
        }
        $item=ShopItem::where('id',$request->item_id)->first();
        $user->coins=$user->coins-$item->price;
    
        $user->save();
     
           $Sales = Sales::create([
            'item_id'=> $request->item_id,
            'user_id'=> $request->user_id,
            "day"=>$request->day,
            "price"=>$item->price,
           "category_id"=>$request->category_id,
           ]);
         $time= Carbon::now()->addDays($request->day);
         $Sales-> created_at=$time ;
         $Sales->save();
        if($Sales){ 
            return $this->returnData('Sales',$Sales);
            }else{
                return $this->returnError('E001', 'Can\'t add Sales');
             }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function SendItem(Request $request){
    
        try {
        $rules = [
            "item_id" => "required|exists:shop_items,id",
            "user_id" => "required|exists:user_apps,id",
            "reciver"=> "required|exists:user_apps,id",
            "day"=>"required",
            "price"=>"required",
            "category_id"=>"required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
         $user= UserApp::where('id', $request->user_id)->first();
        if($user->coins<$request->price){
            return $this->returnError('E011', 'Can\'t add Sales');
        }
           if( $user->coins<$request->price){
            return 'asd';
        }
                $item=ShopItem::where('id',$request->item_id)->first();

        $user->coins=$user->coins-$item->price;
        $user->save();
     
           $Sales = Sales::create([
            'item_id'=> $request->item_id,
            'user_id'=> $request->reciver,
            "day"=>$request->day,
            "price"=>$item->price,
           "category_id"=>$request->category_id,
           ]);
         $time= Carbon::now()->addDays($request->day);
         $Sales-> created_at=$time ;
         $Sales->save();
        if($Sales){ 
            return $this->returnData('Sales',$Sales);
            }else{
                return $this->returnError('E001', 'Can\'t add Sales');
             }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//--------------------------------------------------


public function Getmyitems(Request $request){
 
    try {
    $rules = [
        "user_id" => "required|exists:user_apps,id",
        "category_id"=>"required",
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
 
       $Sales = Sales::where([['user_id',$request->user_id],['category_id',$request->category_id],['status',1]])->with("item")->get();
     
    if($Sales){ 
        return $this->returnData('Sales',$Sales);
        }else{
            return $this->returnError('E001', 'Can\'t add Sales');
         }
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}
//--------------------------------------------------

public function validselse($id){
    try{
 
 
        
        $sales=Sales::where('user_id',$id)->with('item')->get();
        foreach($sales as $cl) {
            $days = $cl->created_at->diffInDays(Carbon::now());
if($days<$cl->day||$days ==0){
    $cl->status=0;
    $cl->save();
}
 }
 return $sales;
    return $this->returnData('Sales',$sales);
    }  catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
  


}
    
}
