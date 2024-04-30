<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Validator;
use Auth;
use App\Models\UserGifts;
use App\Models\UserApp;
use App\Models\JoinAgencyRequest;
class JoinAgencyRequestController extends Controller
{  use GeneralTrait;

    public function RequestJoinAgency(Request $request){
        try {
            $rules = [
                "user_id" => "required",
                "agancy_id" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $user=UserApp::where('id', $request->user_id)->first();
          if($user->AgencyId!=null){
            return $this->returnError('E001', 'Can\'t add JoinAgencyRequest');

       
          }
           $JoinAgencyRequest = JoinAgencyRequest::create([
            "user_id"=>  $request->user_id,
            "agancy_id"=>  $request->agancy_id,
        ]);
     
        if($JoinAgencyRequest){
            return $this->returnData('JoinAgencyRequest',$JoinAgencyRequest);
            }else{
                return $this->returnError('E001', 'Can\'t add JoinAgencyRequest');
         }  
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function GetJoinRequests($id){
        try {
         $request=JoinAgencyRequest::where('agancy_id',$id)->with('user')->get();
         return $this->returnData('request',  $request);
      } catch (\Exception $ex) {
          return $this->returnError($ex->getCode(), $ex->getMessage());
      }
       }
       public function  AcceptJoinRequests($id){
        try {
         $request=JoinAgencyRequest::where('id',$id)->first();
         
         
         $user=UserApp::find( $request->user_id)->update(['AgencyKarisma'=>0,'AgencyId'=>$request->agancy_id]);
         
                
        //          $UserGifts = UserGifts::create([
        //     "user_id"=> $request->user_id,
        //     "svga"=>  "1678145897.svg",
        //     "title" =>  '',
        //     "message" =>  '',
        //     "image" => "1678145897.png",
        //     "kind" =>  1,
        // ]);
               
        //          $UserGifts->created_at = $UserGifts->created_at->addDays(100);
        // $UserGifts->save() ;
         $request->delete();
         return $this->returnData('request',  $request);
      } catch (\Exception $ex) {
          return $this->returnError($ex->getCode(), $ex->getMessage());
      }
       }
       public function  refuseJoinRequests($id){
        try {
         $request=JoinAgencyRequest::where('id',$id)->first();
          $request->delete();
         return $this->returnData('request',  $request);
      } catch (\Exception $ex) {
          return $this->returnError($ex->getCode(), $ex->getMessage());
      }
       }



    }
