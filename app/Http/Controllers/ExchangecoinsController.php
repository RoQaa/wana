<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Traits\GeneralTrait;
 use App\Models\UserApp;
use Validator;
use App\Models\Exchangecoins;
class ExchangecoinsController extends Controller
{
     use GeneralTrait;

    public function Exchangecoins(Request $request){
  
        try {
        $rules = [
            "user_id" => "required",
            "beans" => "required",
         
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
     $user=UserApp::find( $request->user_id);
 //  return  gettype((int)$request->beans);
//  if( $user->AgencyId!=null){
//                   return $this->returnError('E001', 'Can\'t add Exchangecoins');

//  }
      //return  $user->Input;
     if((int)$user->Input < (int)$request->beans||$request->beans<0){
         return 'erroracoure';
     }
     
   
         $user->increment('coins',(int)(($request->beans/100)*30));
         $user->decrement('Input',$request->beans);
           $Exchangecoins = Exchangecoins::create([
            'user_id'=> $request->user_id,
            'beans'=> $request->beans,
            'coins'=> (int)(($request->beans/100)*30),
        ]);
        if($Exchangecoins){
            return $this->returnData('Exchangecoins',['UserCoins'=>$user->coins,'UserInput'=>$user->Input]);
            }else{
                return $this->returnError('E001', 'Can\'t add Exchangecoins');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    
  public function  UserAppInput(){
      $users=UserApp::all();
      foreach ($users as $items){
       $items->ginput  =$items->Input;
       
          $items->save();
      }
      
  }
     public function ReturnExchangecoins(){
   $Exchangecoins = Exchangecoins::all();
   
      for ($i = 0; $i <count($Exchangecoins); $i++){
        $user=UserApp::where('id',$Exchangecoins[$i]->user_id)->first();
          $user->increment('Input',$Exchangecoins[$i]->beans);
         $user->decrement('coins',$Exchangecoins[$i]->coins);
       $Exchangecoins[$i]->delete();
        } 
  
        
    }
}
