<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\chatroom;
use App\Traits\GeneralTrait;
use App\Models\UserApp;
use  App\Events\RoomEvent;

use Validator;
class ChatroomController extends Controller
{
    use GeneralTrait;
    public function AddChatRoom(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "room_id" => "required",
            "content"=>"required"
         ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
 
           $ChatRoom = chatroom::create([
            'user_id'=>$request->user_id,
            'room_id'=>$request->room_id,
            'content'=> $request->content,
           
        ]) ;
        $user=UserApp::where('id',$request->user_id)->first();
 
      
       
        $rrrr=array_add( $ChatRoom,'user', $user);
        if($ChatRoom){
         
            event(new RoomEvent(4,$ChatRoom ,$request->room_id));

            return $this->returnData('ChatRoom', $rrrr);
            }else{
                return $this->returnError('E001', 'Can\'t add message');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
     public function AddMention(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "room_id" => "required",
            "content"=>"required",
            "reciver_id"=>"required"
         ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
 
    
        $user=UserApp::where('id',$request->user_id)->first();
         $reciveruser=UserApp::where('id',$request->reciver_id)->first();
 
        if( $user){
         
            event(new RoomEvent(23,['user'=> $user,'reciveruser'=>$reciveruser,'content'=>$request->content] ,$request->room_id));

            return $this->returnData('ChatRoom', 'ChatRoom');
            }else{
                return $this->returnError('E001', 'Can\'t add message');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }  
      public function SendImage(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "room_id" => "required",
            "image"=>"required",
             
         ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
 
    
        $user=UserApp::where('id',$request->user_id)->first();
          $image='';
        if($request->hasfile('image')){
            $fileName =time().'.png';   
            $file1 = $request->image->move(public_path('images'),$fileName);
            $image=$fileName;
           }
    
 
        if( $user){
         
            event(new RoomEvent(24,['user'=> $user,'content'=>$image] ,$request->room_id));

            return $this->returnData('ChatRoom', 'ChatRoom');
            }else{
                return $this->returnError('E001', 'Can\'t add message');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }  
    public function  GetChatRoom(Request $request){

        try {
      
            $chatroom=chatroom::where('state',0)->get();
            return $this->returnData('chatroom', $chatroom );
         } catch (\Exception $ex) {
             return $this->returnError($ex->getCode(), $ex->getMessage());
         }

    }
}
