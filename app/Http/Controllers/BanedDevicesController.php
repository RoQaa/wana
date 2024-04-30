<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\baned_devices;
use App\Traits\GeneralTrait;
use Validator;
use  App\Events\UserEvent;
use App\Models\UserApp;
use Storage as ss;
use Carbon\Carbon;
class BanedDevicesController extends Controller
{
    use GeneralTrait;
 

    public function BanDevice($id){
        try {
        $user=UserApp::find($id);
        
     
         $user->update(['ban'=>'1']);
   
           $Baned = baned_devices::create([
            'user_id'=>$user->id,
            'reason'=> '',
            'deviceid'=>$user->deviceId,
        ]);
        
               event(new UserEvent(17,['coins'=>''],$user->id));
        if($Baned){
            return $this->returnData('Baned',$Baned);
            }else{
                return $this->returnError('E001', 'Can\'t add  Baned');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function BandAccount($id){
        try {
        $user=UserApp::find($id);
        
     
         $user->update(['ban'=>'1']);
   
       
               event(new UserEvent(17,['coins'=>''],$user->id));
        if($user){
            return $this->returnData('Baned',$user);
            }else{
                return $this->returnError('E001', 'Can\'t add  Baned');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

   public function BanDeviceTime($id,$time){
        try {
        $user=UserApp::find($id);
        
     
         $user->update(['ban'=>'1']);
   
           $Baned = baned_devices::create([
            'user_id'=>$user->id,
            'kind'=>1,
            'reason'=>'',
            'deviceid'=>$user->deviceId,
        ]);
          $Baned->created_at = $Baned->created_at->addDays($time);
        $Baned->save() ;
               event(new UserEvent(17,['coins'=>''],$id));
        if($Baned){
            return $this->returnData('Baned',$Baned);
            }else{
                return $this->returnError('E001', 'Can\'t add  Baned');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    public function RemoveTimeBanned(){
           $Baned = baned_devices::where('kind',1)->get();
           
               foreach( $Baned as $cl) {
                $days =Carbon::now()->diffInDays($cl->created_at,false);
              
            if($days<0 ){
            $user=UserApp::find($cl->user_id);
        $user->update(['ban'=>0]);
    
        $Baned = baned_devices::where('user_id',$cl->user_id)->delete();
         
    }  
     }
          
           
    }

    public function RemoveBanDevice($id){
        try {
        $user=UserApp::find($id);
        $user->update(['ban'=>0]);
    
        $Baned = baned_devices::where('user_id',$id)->delete();
        
        if($Baned){
            return $this->returnData('Baned',$Baned);
            }else{
                return $this->returnError('E001', 'Can\'t add  Baned');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
