<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StartBanner;
use App\Traits\GeneralTrait;
use Validator;
class StartBannerController extends Controller
{
    use GeneralTrait;
    public function AddStartBanner(Request $request){
        try {
        $rules = [
          //  "Room_id" => "required",
            "image" => "required",
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
            $photo=$fileName;
           }

           $StartBanner = StartBanner::all()->first();
           $StartBanner->photo= $photo;
           $StartBanner->save();
       
        if(  $StartBanner){
            return $this->returnData('Banner' , $StartBanner);
            }else{
                return $this->returnError('E001', 'Can\'t add Banner');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    function ChangeStartBannerState($id){
        $Banners=StartBanner::where('id',$id)->first();
        if($Banners->status==1){
            $Banners->status=0;
        }else{
            $Banners->status=1;
        }
        $Banners->save();
         return $this->returnData('Banner',$Banners);        
        }
}
