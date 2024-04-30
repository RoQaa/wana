<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;  
use App\Traits\GeneralTrait;
use Validator;
 
use Illuminate\Support\Str;
use App\Models\ProfileImages;
class ProfileImagesController extends Controller
{
    use GeneralTrait;
    public function AddProfileImage(Request $request)
    {
        try {
    
        $rules = [
            'user_id' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:3048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $image=null;
        if($request->hasfile('image')){
            $fileName =time().'.png';   
            $file1 = $request->image->move(public_path('images'),$fileName);
            $image=$fileName;
           }

        $ProfileImages = ProfileImages::create([
                'user_id' => $request->user_id,
                'image'=> $image,
              
            ]);
            return $this->returnData('ProfileImages', $ProfileImages);


    } catch (\Exception $ex) {
 
         return $this->returnError($ex->getCode(), $ex->getMessage());
    }

    }
      public function DeleteProfileImage($id){
            $ProfileImages=ProfileImages::where('id',$id)->first();
           // return $ProfileImages->image;
    //          $path=strstr('1677413780.png',"images");
    //          return $path;
    //              if(File::exists($path)){
    //           File::delete($path);
    //   }
      
      $ProfileImages->delete();
      }
}
