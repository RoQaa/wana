<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Visitors;
class VisitorsController extends Controller
{
    use GeneralTrait;
    //--------------------------------------AddCategory
    public function AddVisitors(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "visitor_id" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }


        $Visitors = Visitors::where([['user_id',$request->user_id],['visitor_id',$request->visitor_id]])->first();
           if($Visitors!=null){
            return $this->returnError('E001', 'Can\'t add Visitors');
           }
        $Visitors = Visitors::create([
            'user_id'=> $request->user_id,
            'visitor_id'=> $request->visitor_id,
        ]);
        if($Visitors){
            return $this->returnData('Visitors',$Visitors);
            }else{
                return $this->returnError('E001', 'Can\'t add Visitors');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function Getmyvisitors($id){
        try {
    $visitor=Visitors::where('user_id',$id)->with('user')->orderBy('created_at', 'DESC')->get();

    return $this->returnData('visitor',$visitor);
    
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

}
