<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Models\Freinds;
use App\Models\UserApp;
use App\Models\follow;
use Validator;
class FreindsController extends Controller
{

    use GeneralTrait;

    
    function getfrind($id){
        $frinds=[];
        $frindinfo=Freinds::where([['sener_id',$id],['state','1']])->orwhere([['user_id',$id],['state','1']])->with('user','users')->get();
        for ($i = 0; $i <count($frindinfo); $i++){
         if($frindinfo[$i]->user->id!=$id){
             $frinds[] =$frindinfo[$i]->user;
         }
         if($frindinfo[$i]->users->id!=$id){
             $frinds[] =$frindinfo[$i]->users;
         }
        } 
        return array_unique($frinds);

 }
 
 
    public function GetMyFriends($id){
        $followers=follow::where('user_id',$id)->get()->pluck('sender_id');
        $followeing=follow::where('sender_id',$id)->get()->pluck('user_id');

        $Freinds=UserApp::whereIn('id',$followers)->whereIn('id',$followeing)->get();
        return  $Freinds;
    }
         public function CheckFrindstateFriends($id,$hisid){
             
        $followers=follow::where('user_id',$id)->get()->pluck('sender_id');
        $followeing=follow::where('sender_id',$id)->get()->pluck('user_id');

        $Freinds=UserApp::where('id',$hisid)->whereIn('id',$followers)->whereIn('id',$followeing)->first();
        if($Freinds==null){
              return $this->returnError('E001', 'Not Frind');
        }else{
               return  $Freinds;
        }
      //  $user=UserApp::whereIn('id',$Freinds)->first();
     
    }
   
    public function sendrequest(Request $request){
        try {
            $rules = [
                'user_id'=>'required',
                'sener_id'=>'required',
            ];

            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
          
               $Freinds = Freinds::create([
                'state' =>'0',
                'user_id'=>$request->user_id,
                'sener_id'=>$request->sener_id,
                 
            ]);
    
              if($Freinds){
                return $this->returnData('Freinds',$Freinds);
                }else{
                    return $this->returnError('E001', 'cant add');
             }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }



    
 function getfrindsrequest($id){
    try {
    $frindinfo=Freinds::where([['user_id',$id],['state','0']])->with('user')->get();
    return $this->returnData('Freinds',  $frindinfo);
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}
 
function getuserfrinds($id){
    try {
 
    return$this-> getfrind($id);
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}
 
function acceptfrindrequest(Request $request){
    try {
        $frendrequest=tap(Freinds::where('id',$request->id)->with('user'))->update(array('state' => '1'))->first();
        return $this->returnData('Freinds', $frendrequest);
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }

}


    public function removefreind($id,$frindid){
    
        $Freinds=Freinds::where([['user_id',$id],['sener_id',$frindid]])->orwhere([['user_id',$frindid],['sener_id',$id]])->delete();
        if($Freinds){
          return 'deleted';
        }
        return 'notdeleted';

      }
      public function removerequest($id){
    
        $Freinds=Freinds::where('id',$id)->delete();
        if($Freinds){
          return 'deleted';
        }
        return 'notdeleted';

      }
}
