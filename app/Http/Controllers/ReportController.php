<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Report;
use App\Traits\GeneralTrait;
class ReportController extends Controller
{
    use GeneralTrait;
    public function SendReport(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "feedback"=>"required",
            "contact"=>"required",
            "feedback_type"=>"required",
            "contact_type"=>"required",

        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $image='';

        if($request->hasfile('image')){
            $fileName =time().'.png';   
            $file1 = $request->image->move(public_path('images'),$fileName);
            $image=$fileName;
           } 
           $Report = Report::create([
            'user_id'=> $request->user_id,
            'image'=> $image,
            'feedback'=> $request->feedback,
            'contact'=> $request->contact,
            'feedback_type'=> $request->feedback_type,
            'contact_type'=> $request->contact_type,
        ]);
        if($Report){
            return $this->returnData('Report',$Report);
            }else{
                return $this->returnError('E001', 'Can\'t add Report');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
