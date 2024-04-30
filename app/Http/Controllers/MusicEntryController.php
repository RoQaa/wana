<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
 

use App\Models\MusicEntry;
class MusicEntryController extends Controller
{ 
    use GeneralTrait;
    public function AddMusicEntry(Request $request){
        try {
        $rules = [
            "country" => "required|unique:music_entries,country",
            "music" => "required",
          
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
        $music='';
     
        if($request->hasfile('music')){
            $fileName =time().'.mp3';   
            $file1 = $request->music->move(public_path('images'),$fileName);
            $music=$fileName;
           } 
          
           $MusicEntry = MusicEntry::create([
            'music'=> $music,
            'country'=>$request->country,
        ]);
    
        if($MusicEntry){
            return $this->returnData('MusicEntry',$MusicEntry);
            }else{
                return $this->returnError('E001', 'Can\'t add MusicEntry');
         }  
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
}
