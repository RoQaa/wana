<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\insult;
use App\Traits\GeneralTrait;
use Validator;
class InsultController extends Controller
{
    use GeneralTrait;
    public function Addinsult(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "type" => "required",
            "text"=>"required",
         ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
     

           $insult = insult::create([
            'user_id'=>$request->user_id,
            'type'=>$request->type,
            'text'=>$request->text,
        ]);
        if($insult){
            return $this->returnData('insult',$insult);
            }else{
                return $this->returnError('E001', 'Can\'t add insult');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

}
