<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
use App\Models\background;
use App\Traits\GeneralTrait;
use Validator;
use App\Models\UserApp;
use App\Models\Joinroom;

class BackgroundController extends Controller
{

    use GeneralTrait;
    public function AddBackground(Request $request){
        try {
        $rules = [
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
            $image=$fileName;
           }
    
           $Background = background::create([
            'image'=> $image,
           
        ]);
        if($Background){
            return $this->returnData('Background',$Background);
            }else{
                return $this->returnError('E001', 'Can\'t add Background');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
   public function GetUserHours(){
             $Joinroom=Joinroom::where(1)->get();
     //   $users=Joinroom::all();s
               return  $Joinroom;
       $timesheetHours = 0;
       return 'asd';
foreach ($users as $user) {
        $timesheetHours += $user->timesheets()->whereDate('created_at', Carbon::today())->get()->sum('hours');
 }
 return    $timesheetHours;
 
    }

}
