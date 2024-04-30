<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use  App\Events\RoomEvent;
use App\Models\emoji;

class EmojiController extends Controller
{
     use GeneralTrait;
    public function Addemoji(Request $request){
       try {
        $rules = [
            "name" => "required",
         
            "emoji"=> 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
        
           $svga='';
           if($request->hasfile('emoji')){
               $fileName =time().'157'.'.gif';   
               $file1 = $request->emoji->move(public_path('images'),$fileName);
               $svga=$fileName;
              }
           $emoji = emoji::create([
             'emoji_Name'=> $request->name,
             'image'=>$svga,
             'emoji_svga'=>$svga,
             'category_id'=> $request->category_id,
        ]);
        if($emoji){
            return $this->returnData('emoji',$emoji);
            }else{
                return $this->returnError('E001', 'Can\'t add emoji');
         }  
    } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
        
    }
       public function Sendemoji(Request $request){
       try {
        $rules = [
            "room_id" => "required",
            "emoji"=> 'required',
            "user_id"=> 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
      
        if($request->emoji!=null){
              event(new RoomEvent(17,['emoji'=>$request->emoji,'user'=> $request->user_id],$request->room_id));
            return $this->returnData('emoji',$request->emoji);
            }else{
                return $this->returnError('E001', 'Can\'t sent emoji');
         }  
    } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
        
    }
    
}
