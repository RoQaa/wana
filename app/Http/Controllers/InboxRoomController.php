<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InboxRoom;
use App\Traits\GeneralTrait;
use Validator;
use App\Models\Messages;
use App\Models\BlockList;
use App\Models\UserApp;
class InboxRoomController extends Controller
{
    use GeneralTrait;
    public function CreateInboxRoom(Request $request){
        try {
        $rules = [
            "user_id" => "required",
            "sender_id" => "required",
            "last_message" => "required",

         ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     
 
           $InboxRoom = InboxRoom::create([
            'user_id'=>$request->user_id,
            'sender_id'=>$request->sender_id,
            'last_message'=> $request->last_message,  
        ]);
        if($InboxRoom){
            return $this->returnData('InboxRoom', $InboxRoom);
            }else{
                return $this->returnError('E001', 'Can\'t add InboxRoom');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }





    public function ReadInboxRoom(Request $request){

        try {
            $rules = [
                "inboxroomid" => "required",
            
             ];
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
         
     
               $InboxRoom = InboxRoom::where('id',$request->inboxroomid)->first(); 
               $InboxRoom -> number_unread =0;
               $InboxRoom ->save();
            if($InboxRoom){
                return $this->returnData('InboxRoom', $InboxRoom);
                }else{
                    return $this->returnError('E001', 'Can\'t add InboxRoom');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }


    }




    public function deleteInboxRoom($id){
 
    $InboxRoom= InboxRoom::where([['id',$id],['status',1]])->delete();
    $Message= Messages::where('Inboxroom_id',$id)->delete();
    return  $InboxRoom;
    }

    public function deleteInboxRoomandBlockUser($id,$Senderid){
        $Inbox= InboxRoom::where([['id',$id],['status',1]])->first();
        $InboxRoom= InboxRoom::where([['id',$id],['status',1]])->delete();
        $Message= Messages::where('Inboxroom_id',$id)->delete();
        if($Inbox->user_id==$Senderid){
            $BlockList = BlockList::create([
                'user_id'=> $Inbox->sender_id,
                'sender_id'=> $Senderid,
              
            ]);
        }else{
            $BlockList = BlockList::create([
                'user_id'=> $Inbox->user_id,
                'sender_id'=> $Senderid,
              
            ]);  
        }
      
        return  $InboxRoom;
        }

    public function GetMyInboxRoomAdmin($id){
    $user=UserApp::where('myappid',$id)->orwhere('Newid',$id)->first();
    $InboxRoom= InboxRoom::where([['user_id',$user->id],['status',1]])->orwhere([['sender_id',$user->id],['status',1]])->with('message','user','sender')->get();
    return  ['inbox'=>$InboxRoom,'id'=>$user->id];
    }
    


    public function GetMyInboxRoom($id){

    $InboxRoom= InboxRoom::where([['user_id',$id],['status',1]])->orwhere([['sender_id',$id],['status',1]])->with('message','user','sender')->get();
    return  $InboxRoom;
    }
    
        public function GetMyInboxRoomDashboard($id){
        $user=UserApp::where('myappid',$id)->orwhere('Newid',$id)->first();


    $InboxRoom= InboxRoom::where([['user_id',$user->id],['status',1]])->orwhere([['sender_id',$user->id],['status',1]])->with('message','user','sender')->get();
    return  $InboxRoom;
    }
    
    
    
    public function ChatWithuser($myid,$userid){

        $InboxRoom= InboxRoom::where([['user_id',$myid],['sender_id',$userid]])->orwhere([['sender_id',$myid],['user_id',$userid]])->with('message','user','sender')->first();

        return  $InboxRoom;
        }




}
