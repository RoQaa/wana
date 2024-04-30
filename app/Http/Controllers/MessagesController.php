<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use App\Models\Messages;
use  App\Events\UserEvent;
use App\Models\InboxRoom;
use App\Models\UserApp;
use App\Models\gift;
use App\Models\Families;
use App\Models\Leaderboard;
class MessagesController extends Controller
{
    use GeneralTrait;
    public function sendmessage(Request $request){
   
       try {
       $rules = [
           "message"=>"required",
           "user_id" => "required",
           "sender_id" => "required",
        ];
       $validator = Validator::make($request->all(), $rules);
   
       if ($validator->fails()) {
           $code = $this->returnCodeAccordingToInput($validator);
           return $this->returnValidationError($code, $validator);
       }   
       //$inbox=InboxRoom::where('user_id',$request->user_id)->orwhere('sender_id',$request->user_id)->orwhere('sender_id',$request->sender_id)->orwhere('user_id',$request->sender_id)->first();
       $current_datetime = Carbon::now();
       $inbox=InboxRoom::where([['user_id',$request->user_id],['sender_id',$request->sender_id],['status',1]])->orwhere([['sender_id',$request->user_id],['user_id',$request->sender_id],['status',1]])->first();
     if($inbox!=null){
        $inbox->number_unread=$inbox->number_unread+1;
        $inbox->last_message=$request->message;
        $inbox->updated_at=$current_datetime;
        $inbox->save();
      
        $Messages = Messages::create([
            "message"=>$request->message,  
            "user_id" =>$request->user_id,  
            "sender_id" =>$request->sender_id,  
            "Inboxroom_id" => $inbox->id,
            "status" =>0,
       ]);
       event(new UserEvent(5,[ 'Messages'=>$Messages],$request->user_id));
       event(new UserEvent(5,[ 'Messages'=>$Messages],$request->sender_id));
     }else{
        $InboxRoom = InboxRoom::create([
            'user_id'=>$request->user_id,
            'sender_id'=>$request->sender_id,
            'last_message'=> $request->message,  
        ]);

    
        $Messages = Messages::create([
            "message"=>$request->message,  
            "user_id" =>$request->user_id,  
            "sender_id" =>$request->sender_id,  
            "Inboxroom_id" => $InboxRoom->id,  
                 "status" =>0,
       ]);
       $inboxroomcontent = InboxRoom::where('id',$InboxRoom->id)->with('message','user','sender')->first();

       event(new UserEvent(4,['InboxRoom'=>$inboxroomcontent],$request->user_id));
       event(new UserEvent(4,['InboxRoom'=>$inboxroomcontent],$request->sender_id));
     }
       if($Messages){
           return $this->returnData('Messages',$Messages);
           }else{
               return $this->returnError('E001', 'Can\'t join this room');
        }  
       } catch (\Exception $ex) {
           return $this->returnError($ex->getCode(), $ex->getMessage());
       }
   }

   public function sendgiftmessage(Request $request){
   
    try {
    $rules = [
        
        "user_id" => "required",
        "sender_id" => "required",
        "gift_id"=> "required",
     ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }   
    $gift=gift::where('id',$request->gift_id)->first();
    $sender=UserApp::where('id',$request->sender_id)->first();
    
    $user=UserApp::where('id',$request->user_id)->first();
    
    if( $sender->coins <$gift->price){
        return 'error';
    }
    $sender->decrement('coins',$gift->price);
    $sender->increment('Karisma',$gift->price);
    $user->increment('Input',$gift->price);
    $user->increment('coins',$gift->price);


     $current_datetime = Carbon::now();
    $inbox=InboxRoom::where([['user_id',$request->user_id],['sender_id',$request->sender_id],['status',1]])->orwhere([['sender_id',$request->user_id],['user_id',$request->sender_id],['status',1]])->first();
  if($inbox!=null){
     $inbox->number_unread=$inbox->number_unread+1;
     $inbox->last_message='Gift';
     $inbox->updated_at=$current_datetime;
     $inbox->save();
   
     $Messages = Messages::create([
        "message"=>$gift->image,    
         "user_id" =>$request->user_id,   
         "sender_id" =>$request->sender_id,  
         "Inboxroom_id" => $inbox->id,
         "status" =>2,
    ]);
    event(new UserEvent(5,[ 'Messages'=>$Messages],$request->user_id));
    event(new UserEvent(5,[ 'Messages'=>$Messages],$request->sender_id));
  }else{
     $InboxRoom = InboxRoom::create([
         'user_id'=>$request->user_id,
         'sender_id'=>$request->sender_id,
         'last_message'=> 'Gift',  
     ]);

 
     $Messages = Messages::create([
         "message"=>$gift->image,  
         "user_id" =>$request->user_id,  
         "sender_id" =>$request->sender_id,  
         "Inboxroom_id" => $InboxRoom->id,  
          "status" =>2,
    ]);
    $inboxroomcontent = InboxRoom::where('id',$InboxRoom->id)->with('message','user','sender')->first();

    event(new UserEvent(4,['InboxRoom'=>$inboxroomcontent],$request->user_id));
    event(new UserEvent(4,['InboxRoom'=>$inboxroomcontent],$request->sender_id));
  }
    if($Messages){
        return $this->returnData('Messages',$Messages);
        }else{
            return $this->returnError('E001', 'Can\'t join this room');
     }  
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
}

    public function sendImage(Request $request){
   
       try {
       $rules = [
           "message"=>"required",
           "user_id" => "required",
           "sender_id" => "required",
        ];
       $validator = Validator::make($request->all(), $rules);
   
       if ($validator->fails()) {
           $code = $this->returnCodeAccordingToInput($validator);
           return $this->returnValidationError($code, $validator);
       }   
          $image=null;
          if($request->hasfile('message')){
            $fileName =time().'.png';   
            $file1 = $request->message->move(public_path('images'),$fileName);
            $image=$fileName;
           }
        
       //$inbox=InboxRoom::where('user_id',$request->user_id)->orwhere('sender_id',$request->user_id)->orwhere('sender_id',$request->sender_id)->orwhere('user_id',$request->sender_id)->first();
       $current_datetime = Carbon::now();
       $inbox=InboxRoom::where([['user_id',$request->user_id],['sender_id',$request->sender_id],['status',1]])->orwhere([['sender_id',$request->user_id],['user_id',$request->sender_id],['status',1]])->first();
     if($inbox!=null){
        $inbox->number_unread=$inbox->number_unread+1;
        $inbox->last_message='image';
        $inbox->updated_at=$current_datetime;
        $inbox->save();
        
        
        
       
        
        
      
        $Messages = Messages::create([
            "message"=> $image,  
            "user_id" =>$request->user_id,  
            "sender_id" =>$request->sender_id,  
            "Inboxroom_id" => $inbox->id,
            "status" =>1,
       ]);
       event(new UserEvent(5,[ 'Messages'=>$Messages],$request->user_id));
       event(new UserEvent(5,[ 'Messages'=>$Messages],$request->sender_id));
     }else{
        $InboxRoom = InboxRoom::create([
            'user_id'=>$request->user_id,
            'sender_id'=>$request->sender_id,
            'last_message'=> 'image',  
        ]);

    
        $Messages = Messages::create([
            "message"=> $image,  
            "user_id" =>$request->user_id,  
            "sender_id" =>$request->sender_id,  
            "Inboxroom_id" => $InboxRoom->id,
            "status" =>1,  
       ]);
       $inboxroomcontent = InboxRoom::where('id',$InboxRoom->id)->with('message','user','sender')->first();

       event(new UserEvent(4,['InboxRoom'=>$inboxroomcontent],$request->user_id));
       event(new UserEvent(4,['InboxRoom'=>$inboxroomcontent],$request->sender_id));
     }
       if($Messages){
           return $this->returnData('Messages',$Messages);
           }else{
               return $this->returnError('E001', 'Can\'t join this room');
        }  
       } catch (\Exception $ex) {
           return $this->returnError($ex->getCode(), $ex->getMessage());
       }
   }
   
   
}
