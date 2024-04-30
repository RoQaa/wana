<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guessgame;
use Validator;
use App\Models\UserApp;
use App\Traits\GeneralTrait;
use  App\Events\RoomEvent;
use  App\Events\UserEvent;
class GuessgameController extends Controller
{
    use GeneralTrait;
    public function  Getguess(){
        $image='';
        $Random= mt_rand(1, 3);
        return $Random;
     
    }
    public function NewGuessgame(Request $request){
       try {
        $rules = [
            "Sender_id" => "required",
            "Sender_gueess" => "required",
            "Coins"=> "required",
            "Room_id"=> "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $guess=$this->Getguess();
        $user=UserApp::where('id',$request->Sender_id)->first();

           $Guessgame = Guessgame::create([
            'Sender_id'=> $request->Sender_id,
            'Sender_gueess'=> $request->Sender_gueess,
            'Coins'=>$request->Coins,
            'status'=>0,
        
        ]);
      // $user->decrement('coins', $request->Coins);

        event(new RoomEvent(26,['user'=> $user,'Guessgame'=>$Guessgame,'Guess'=>$request->Sender_gueess,'Coins'=>$request->Coins,'Guessgameid'=>$Guessgame->id],$request->Room_id));
        if($Guessgame){
            return $this->returnData('Guessgame',$Guessgame);
            }else{
                return $this->returnError('E001', 'Can\'t add Guessgame');
         }  
    } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
        
    }
    
        public function AcceptGuessgame(Request $request){
        try {
         $rules = [
             "guess_id" => "required",
             "Accept_id" => "required",
             "Accept_gueess" => "required",
             "Room_id"=> "required",
         ];
         $validator = Validator::make($request->all(), $rules);
         if ($validator->fails()) {
             $code = $this->returnCodeAccordingToInput($validator);
             return $this->returnValidationError($code, $validator);
         }
         $Guessgame= Guessgame::where([['id',$request->guess_id],['status',0]])->first();
         if( $Guessgame==null){
              return $this->returnError('E001', 'Can\'t add Guessgame');
         }
         $user=UserApp::where('id',$Guessgame->Sender_id)->first();
         $user->decrement('coins', $Guessgame->Coins);
         event(new UserEvent(10,['coins'=>$Guessgame->Coins],$user->id));
         
         $Acceptuser=UserApp::where('id',$request->Accept_id)->first();
$winner=0;
  $Acceptuser->decrement('coins', $Guessgame->Coins);
  if(($Guessgame->Sender_gueess==1&&$request->Accept_gueess==1)||($Guessgame->Sender_gueess==2&&$request->Accept_gueess==2)||($Guessgame->Sender_gueess==3&&$request->Accept_gueess==3)){
    $user->increment('coins', $Guessgame->Coins);
    $Acceptuser->increment('coins',$Guessgame->Coins);
    event(new UserEvent(6,['coins'=>$Guessgame->Coins],$user->id));
    event(new UserEvent(6,['coins'=>$Guessgame->Coins],$Acceptuser->id));
  }else if($Guessgame->Sender_gueess==1&&$request->Accept_gueess==2){
    $user->increment('coins',(($Guessgame->Coins/100)*80)+$Guessgame->Coins);
    event(new UserEvent(6,['coins'=>(($Guessgame->Coins/100)*80)+$Guessgame->Coins],$user->id));
    $winner=$user->id;
  }else if($Guessgame->Sender_gueess==1&&$request->Accept_gueess==3){
    $Acceptuser->increment('coins', (($Guessgame->Coins/100)*80)+$Guessgame->Coins);
    event(new UserEvent(6,['coins'=>(($Guessgame->Coins/100)*80)+$Guessgame->Coins],$Acceptuser->id));
    $winner=$Acceptuser->id;
  }else if($Guessgame->Sender_gueess==2&&$request->Accept_gueess==3){
    $user->increment('coins',(($Guessgame->Coins/100)*80)+$Guessgame->Coins);
    event(new UserEvent(6,['coins'=>(($Guessgame->Coins/100)*80)+$Guessgame->Coins],$user->id));
    $winner=$user->id;
  }else if($Guessgame->Sender_gueess==2&&$request->Accept_gueess==1){
    $Acceptuser->increment('coins', (($Guessgame->Coins/100)*80)+$Guessgame->Coins);
    event(new UserEvent(6,['coins'=>(($Guessgame->Coins/100)*80)+$Guessgame->Coins],$Acceptuser->id));
    $winner=$Acceptuser->id;
  }else if($Guessgame->Sender_gueess==3&&$request->Accept_gueess==1){
    $user->increment('coins', (($Guessgame->Coins/100)*80)+$Guessgame->Coins);
    event(new UserEvent(6,['coins'=>(($Guessgame->Coins/100)*80)+$Guessgame->Coins],$user->id));
    $winner=$user->id;
  }else if($Guessgame->Sender_gueess==3&&$request->Accept_gueess==2){
    $Acceptuser->increment('coins', (($Guessgame->Coins/100)*80)+$Guessgame->Coins);
    event(new UserEvent(6,['coins'=>(($Guessgame->Coins/100)*80)+$Guessgame->Coins],$Acceptuser->id));
    $winner=$Acceptuser->id;
  }
         $Guessgame->update(['status'=>1,'Accept_id'=>$request->Accept_id,'Accept_gueess'=>$request->Accept_gueess]);
     
        $Guessgame= Guessgame::where('id',$request->guess_id)->first();
        if($winner==0){
            $Guessgame->status=3; 
        }
       
                  $Guessgame->user=$user; 
                 $Guessgame->Reciver=$Acceptuser; 
                 $Guessgame->winner=$winner;
      
         
         event(new RoomEvent(27,['Guessgame'=>  $Guessgame,'winner'=>$winner],$request->Room_id));
         if($Guessgame){
             return $this->returnData('Guessgame',$Guessgame);
             }else{
                 return $this->returnError('E001', 'Can\'t add Guessgame');
          }  
     } catch (\Exception $ex) {
             return $this->returnError($ex->getCode(), $ex->getMessage());
         }
         
     }

}
