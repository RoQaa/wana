<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserApp;
use App\Models\Fruits;
use App\Traits\GeneralTrait;
use Validator;
use Carbon\Carbon;
use  App\Events\UserEvent;
use App\Models\GamesLeaderBoard;
use App\Models\FruitePackage;


 
use App\Models\games;
 

class FruitsController extends Controller
{
    
    
        use GeneralTrait;
      public function FruitContent(){

    try {
        
        
         
        
         $UserApp=UserApp::select('name','coins','id','rememper_token')->where("rememper_token",Request()->token)->first();
          if($UserApp==null){
              return $this->returnError('E001', 'Not Found');
          }
          $Fruits=Fruits::all();
          $Package=FruitePackage::all();
        return $this->returnData('data', [
            'user'=>$UserApp,
            'Fruits'=>$Fruits,
            'Package'=>$Package,
            ] );

   if($UserApp!=null){
 
      return $this->returnData('Users', $UserAp );
    }else{
      return $this->returnError('E001', 'Not Found');

    }
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}}


 
    function generateRandomNumberNotInArray($arr) {
    $random = rand(1, 8);
    while(in_array($random, $arr)) {
        $random = rand(1, 8);
    }
    return $random;
}
 
    public function PLayFruit(Request $request){
  
        try {
        $rules = [
            "user_id" => "required",
            "package_id" => "required",
            "selected"=> "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
       
         $User=UserApp::where('rememper_token',$request->user_id)->first();
         $game=games::where('id',1)->first();
           
         if($User==null){
              return $this->returnError('E001', 'Not Found');
         }
        $Package=FruitePackage::where('id',$request->package_id)->first();
          if($Package==null){
              return $this->returnError('E001', 'Not Found');
         }
         if( $User->coins<$Package->value*count($request->selected)){
             return $this->returnError('E001', 'لا تمتلك عملات كافيه');
         }else{
             
                if(count($request->selected)>5){
            return $this->returnError('E001', 'يمكنك اختيار 4 خيارات فقط');
         }
         
             
              $User->decrement('coins', $Package->value*count($request->selected));
         }
         
         $randomindex= mt_rand(0,8);
          $Fruits=Fruits::where('index',$randomindex)->first();
         $userwin=0;
         
         
         if(in_array($randomindex,$request->selected))
          {
              
              if($game->usersnumber<5000){
            //       if($Fruits->value*$Package->value>5000){
                      
                      
            //       $randomindex= mt_rand(0,4);
            //           $Fruitsss=Fruits::where('index',$randomindex)->first();
            //       if($Fruitsss->value*$Package->value>$game->usersnumber){
            //       $randomindex= mt_rand(0,3);
            //  }
                   
            //  }
          $randomindex=  $this->generateRandomNumberNotInArray($request->selected);
}else if($game->usersnumber>100000){
      $randomindex= mt_rand(0,8);
      
      
}else  if($game->usersnumber>=50000){
             
             if($Fruits->value*$Package->value>50000){
                   $randomindex= mt_rand(0,4);
             }
             
             
             
         }else{
         
              if(count($request->selected)>=4){
                     
              $randomindex= mt_rand(0,5);
                 $Fruitss=Fruits::where('index',$randomindex)->first();
                 
                if($Fruitss->value*$Package->value>$game->usersnumber){
                   $randomindex= mt_rand(0,4);
             }
         }else{
             
                if($Fruits->value*$Package->value>$game->usersnumber){
                   $randomindex= mt_rand(0,4);
             }else{
                 
             }
             
             
             
         }
             
         }

          }
       
         
       
             
         
       
         if(in_array($randomindex, $request->selected)){
         //   $game->increment('usersnumber',$userwin);
             
            
             $userwin=  $Fruits->value*$Package->value;
             $User->increment('coins', $userwin);
                        event(new UserEvent(9,['coins'=>$User->coins],$User->id));
  $game->decrement('usersnumber',$userwin);
             GamesLeaderBoard::create([
                'user_id'=>  $User->id,
                'status'=>  1,
                'coins'=> $Package->value*count($request->selected),
                'resultcoins'=>$userwin,
                'game'=>'fruit'
           
            ]);
             
              return $this->returnData('result',['state'=>'win','index'=>$randomindex,'Coins'=>$userwin,'UserCoins'=>$User->coins]);
             
         }else{
             
                                     event(new UserEvent(9,['coins'=>$User->coins],$User->id));
 $game->increment('usersnumber',$Package->value*count($request->selected));
              GamesLeaderBoard::create([
                'user_id'=>  $User->id,
                'status'=>  0,
                'coins'=> $Package->value*count($request->selected),
                'resultcoins'=>$Package->value*count($request->selected),
                'game'=>'fruit'
           
            ]);
              return $this->returnData('result',['state'=>'lose','index'=>$randomindex,'UserCoins'=>$User->coins]);
         }
        
     
      
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


 public function ResetToken(){
     
      //return Carbon::now();
        $UserApp=UserApp::where('rememper_token',null)->get();
           foreach( $UserApp as $cl) {
            $cl->rememper_token=str_random(100).bcrypt(Carbon::now()).$cl->id;
               $cl->save(); 
           }
        return  $UserApp;
     
     return str_random(100).bcrypt(Carbon::now()).'125';
 }


    
}
