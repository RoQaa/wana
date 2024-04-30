<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Shipping;
use App\Models\UserApp;
class ShippingController extends Controller
{
    use GeneralTrait;
//     public function shippingccount(Request $request){
//       try {
//       $rules = [
//           "user_id" => "required|exists:user_apps,id",
//           "coins" => "required",
//           "method" => "required",
//           "cost" => "required",
//       ];
//       $validator = Validator::make($request->all(), $rules);
   
//       if ($validator->fails()) {
//           $code = $this->returnCodeAccordingToInput($validator);
//           return $this->returnValidationError($code, $validator);
//       }
//           UserApp::find($request->user_id)->increment('coins',$request->coins);
//           $shipping = Shipping::create([
//           'coins'=>$request->coins,
//           'user_id'=>$request->user_id,
//           'method'=>$request->method,
//           'cost'=>$request->cost,
//       ]);
//       if($shipping){
//           return $this->returnData('shipping',$shipping);
//           }else{
//               return $this->returnError('E001', 'Can\'t join this room');
//         }  
//       } catch (\Exception $ex) {
//           return $this->returnError($ex->getCode(), $ex->getMessage());
//       }
//   }
   
}
