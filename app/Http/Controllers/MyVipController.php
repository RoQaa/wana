<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Validator;
use App\Traits\GeneralTrait;
use App\Models\MyVip;
use Carbon\Carbon;
use App\Models\UserApp;
use Storage as ss;
use App\Models\VipCenter;
class MyVipController extends Controller
{
    use GeneralTrait;
    public function ByeVip(Request $request){
        try {
        $rules = [

            "user_id"=>"required",
            "vip_id"=>"required",
            "days"=>"required",
            "cost"=>"required",
           
        ];

        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
      
        $myVip = MyVip::where([['user_id',$request->user_id],['status',1]])->first();
        $Vip = VipCenter::where('id', $request->vip_id)->first();
    
        
     if($Vip->cost!=$request->cost){
             return $this->returnError('E011', 'Can\'t add vip');
     }
        
$user= UserApp::where('id', $request->user_id)->first();
if($user->coins< $Vip->cost||$request->cost<0||$user->coins<0){
    return $this->returnError('E011', 'Can\'t add vip');
}else{
    $user->decrement('coins',$Vip->cost);
}


 
 
 
           $MyVip = MyVip::create([
            'user_id'=> $request->user_id,
            'vip_id'=> $request->vip_id,
            'days'=> $request->days,
            'cost'=> $Vip->cost,
            'status'=> 1,
        ]);
        $time= Carbon::now()->addDays($request->days);
        $MyVip-> created_at=$time;
        $MyVip->save();
        $info= $MyVip->where([['status',0],['user_id',$request->user_id]])->with('vip')->first();
      
        if($MyVip){
            return $this->returnData('MyVip',$info);
            }else{
                return $this->returnError('E001', 'Can\'t add MyVip');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

public function AddUserVip(Request $request){
        try {
        $rules = [

            "user_id"=>"required",
            "vip_id"=>"required",
            "days"=>"required",
        ];

        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
      
        $Vip = MyVip::where([['user_id',$request->user_id],['status',1],['vip_id',$request->vip_id]])->first();
if($Vip){
    return $this->returnData('MyVip','Already has this Vip');

}
$user= UserApp::where('id', $request->user_id)->first();
 
 
 

 
           $MyVip = MyVip::create([
            'user_id'=> $request->user_id,
            'vip_id'=> $request->vip_id,
            'days'=> $request->days,
            'cost'=> 0,
            'status'=> 1,
        ]);
        $time= Carbon::now()->addDays($request->days);
        $MyVip-> created_at=$time;
        $MyVip->save();
       $info= $MyVip->where([['status',1],['user_id',$request->user_id],['vip_id',$request->vip_id]])->with('vip')->first();
      
        if($MyVip){
            return $this->returnData('MyVip',$info);
            }else{
                return $this->returnError('E001', 'Can\'t add MyVip');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
     public function AddAppidVip(Request $request){
        try {
        $rules = [

            "id"=>"required",
            "vip_id"=>"required",
            "days"=>"required",
        ];

        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
          $userinfo=UserApp::where('id',$request->id)->first();
          if($userinfo==null){
              return 'ccccccccccccccccccccccccccccccccc';
          }
        $Vip = MyVip::where([['user_id',$userinfo->id],['status',1]])->first();
if($Vip){
    return $this->returnData('MyVip',['asd'=>'Already has this Vip','id'=>$userinfo->id]);

}
$user= UserApp::where('id', $userinfo->id)->first();
 

$myVip = VipCenter::where('id', $request->vip_id)->first();
 
// if($myVip->Level!=null){
    
//   $user->increment('Karisma',$myVip->Level*10000);
  
// }
 
           $MyVip = MyVip::create([
            'user_id'=> $userinfo->id,
            'vip_id'=> $request->vip_id,
            'days'=> $request->days,
            'cost'=> 0,
            'status'=> 1,
        ]);
        $time= Carbon::now()->addDays($request->days);
        $MyVip-> created_at=$time;
        $MyVip->save();
       $info= $MyVip->where([['status',1],['user_id',$userinfo->id]])->with('vip')->first();
      
        if($MyVip){
            return $this->returnData('MyVip',$info);
            }else{
                return $this->returnError('E001', 'Can\'t add MyVip');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function GetMyVips($id){
        try {
            $MyVip=MyVip::where([['user_id',$id],['endstatus',1]])->with('vip')->get();
            return $this->returnData('MyVip',$MyVip);
                  
                } catch (\Exception $ex) {
                    return $this->returnError($ex->getCode(), $ex->getMessage());
                }
    }

    public function UseVip(Request $request){
        try {
            $rules = [
    
                "Myvip_id"=>"required",
                "user_id"=>"required",
                
            ];
    
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $userinfo=UserApp::where('id',$request->user_id)->first();
            MyVip::where('user_id',$request->user_id)->update(['status'=>0]);
            $MyVip=MyVip::where('id',$request->Myvip_id)->with('vip')->first();
            $MyVip->update(['status'=>1]);
            $userinfo->update(['entry'=>null]);
            $userinfo->update(['frameimage'=>null]);
            $userinfo->update(['bubbles'=>null]);
            $userinfo->update(['Hidden'=>0]);
            $userinfo->update(['ColoredMessage'=>null]);
            $userinfo->update(['Newid'=>null]);
            if($MyVip->vip->Entry!=null){
                $userinfo->update(['entry'=>$MyVip->vip->Entry]);
            }

            if($MyVip->vip->Frame!=null){
                $userinfo->update(['frameimage'=>$MyVip->vip->Frame]);
            }

            if($MyVip->vip->ProfileEntry!=null){
                $userinfo->update(['bubbles'=>$MyVip->vip->ProfileEntry]);
            }
           ///// if($MyVip->vip->Hidden!=0){
          //      $userinfo->update(['Hidden'=>1]);
        //    }
            if($MyVip->vip->new_id!=null){
                $userinfo->update(['Newid'=>$MyVip->vip->new_id]);
            }
            if($MyVip->vip->ColoredMessage!=null){
                $userinfo->update(['ColoredMessage'=>$MyVip->vip->ColoredMessage]);
            }
              return $MyVip;


                  
                } catch (\Exception $ex) {
                    return $this->returnError($ex->getCode(), $ex->getMessage());
                }
    }


    public function RemoveVip(Request $request){
        try {
        $rules = [
            "user_id"=>"required",
            "Myvip_id"=>"required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $user= UserApp::where('id', $request->user_id)->first();
        $MyVip=MyVip::where('id',$request->Myvip_id)->with('vip')->first();
        MyVip::where('user_id',$request->user_id)->update(['status'=>0]);
        

        
 if($MyVip->vip->Entry==$user->entry){
    $user->update(['entry'=>null]);
 }
 if($MyVip->vip->Frame==$user->frameimage){
    $user->update(['frameimage'=>null]);
 }
 if($MyVip->vip->ProfileEntry==$user->bubbles){
    $user->update(['bubbles'=>null]);
 }
 if($MyVip->new_id==$user->Newid){
    $user->update(['Newid'=>null]);
 }
 if($MyVip->vip->ColoredMessage==$user->ColoredMessage){
    $user->update(['ColoredMessage'=>null]);
 }

//  if($MyVip->vip->Hidden==1){
//     $user->update(['Hidden'=>0]);
//  }
 
        if($MyVip){
            return $this->returnData('MyVip',$MyVip);
            }else{
                return $this->returnError('E001', 'Can\'t add MyVip');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }




}
