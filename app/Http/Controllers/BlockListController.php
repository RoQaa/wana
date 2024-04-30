<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
 
use App\Models\BlockList;
class BlockListController extends Controller
{
          use GeneralTrait;
    //--------------------------------------BlockList
        public function AddBlockList(Request $request){
            try {
            $rules = [
             "user_id" => "required",
             "sender_id" => "required",
            
            ];
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
         
               $BlockList = BlockList::create([
                'user_id'=> $request->user_id,
                'sender_id'=> $request->sender_id,
              
            ]);
            if($BlockList ){
                return $this->returnData('BlockList',$BlockList);
                }else{
                    return $this->returnError('E001', 'Can\'t add BlockList');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }
        
          public function UnBlockUser(Request $request){
            try {
            $rules = [
             "user_id" => "required",
             "sender_id" => "required",
            ];
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            
             $BlockList =BlockList::where([['user_id',$request->user_id],['sender_id',$request->sender_id]])->first();
          return   $BlockList->delete();
            if($BlockList ){
                return $this->returnData('BlockList',$BlockList);
                }else{
                    return $this->returnError('E001', 'Can\'t add BlockList');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }
}
