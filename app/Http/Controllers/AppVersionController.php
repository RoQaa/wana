<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Traits\GeneralTrait;
 
use Validator;
use App\Models\AppVersion;
class AppVersionController extends Controller
{
    use GeneralTrait;

    public function addversion(Request $request){
  
        try {
        $rules = [
            "version" => "required",
          
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
           $version = AppVersion::create([
            'version'=> $request->version,
            
        ]);
        if($version){
            return $this->returnData('version',$version);
            }else{
                return $this->returnError('E001', 'Can\'t add version');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
