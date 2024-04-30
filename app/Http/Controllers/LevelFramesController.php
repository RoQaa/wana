<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
use Validator;
use App\Traits\GeneralTrait;
 

use App\Models\LevelFrames;
class LevelFramesController extends Controller
{
    use GeneralTrait;
    public function AddLevelFrames(Request $request){
        try {
        $rules = [
             "svga" => "required",
             "image" => "required",
             "level_start" => "required",
             "level_end" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
        $image='';
        $svgimage='';
        if($request->hasfile('image')){
            $fileName =time().'.png';   
            $file1 = $request->image->move(public_path('images'),$fileName);
            $image=$fileName;
           } 
           if($request->hasfile('svga')){
            $fileName =time().'.svg';   
            $file1 = $request->svga->move(public_path('images'),$fileName);
            $svgimage=$fileName;
           }
           $LevelFrames = LevelFrames::create([
            "svga"=>$svgimage,
            "image"=>$image,
            "level_start" =>  $request->level_start,
            "level_end" =>  $request->level_end,
        ]);
       
        if($LevelFrames){
            return $this->returnData('LevelFrames',$LevelFrames);
            }else{
                return $this->returnError('E001', 'Can\'t add LevelFrames');
         }  
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
