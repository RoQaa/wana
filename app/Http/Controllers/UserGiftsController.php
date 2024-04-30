<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
use Validator;
use App\Traits\GeneralTrait;
use App\Models\UserGifts;
 use Carbon\Carbon;
 use App\Models\UserApp;
class UserGiftsController extends Controller
{
    use GeneralTrait;
    public function AddUserGifts(Request $request){
        try {
            $image='';
            $svgimage='';
            if($request->hasfile('image')){
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
                $image=$fileName;
               } 
               if($request->hasfile('svga')){
                $fileName =time().'.svg';   
                $file1 = $request->svga->move(public_path('images'),$fileName);
                $svgimage=$fileName;
               }

           $UserGifts = UserGifts::create([
            "user_id"=>  $request->user_id,
            "svga"=>  $svgimage,
            "title" =>  '',
            "message" =>  '',
            "image" => $image,
            "kind" =>  $request->kind,
        ]);
        $UserGifts->created_at = $UserGifts->created_at->addDays($request->days);
        $UserGifts->save() ;
        if($UserGifts){
            return $this->returnData('UserGifts',$UserGifts);
            }else{
                return $this->returnError('E001', 'Can\'t add UserGifts');
         }  
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
        public function AddUserGifts2(Request $request){
        try {
      

           $UserGifts = UserGifts::create([
            "user_id"=>  $request->user_id,
            "svga"=>  $request->svga,
            "title" =>  '',
            "message" =>  '',
            "image" => $request->image,
            "kind" =>  $request->kind,
        ]);
        $UserGifts->created_at = $UserGifts->created_at->addDays($request->days);
        $UserGifts->save() ;
        if($UserGifts){
            return $this->returnData('UserGifts',$UserGifts);
            }else{
                return $this->returnError('E001', 'Can\'t add UserGifts');
         }  
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
   public function christmas(){
        try {
       
       
           
 $user= UserApp::where('AgencyId',null)->get();
 
 
    foreach( $user as $cl) {
             $UserGifts = UserGifts::create([
            "user_id"=>   $cl->id,
            "svga"=>  '1677332129.svg',
            "title" =>  'هديه من واناسه',
            "message" => '',   
            "image" => '1677332129.png',
            "kind" =>  1,
        
        ]);
         $UserGifts->created_at=Carbon::now()->addDay(7);
        $UserGifts->save();
    }
 
      
        if($UserGifts){
            return $this->returnData('UserGifts',$UserGifts);
            }else{
                return $this->returnError('E001', 'Can\'t add UserGifts');
         }  
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }



    public function GetMyUserGifts($id){
    
        $UserGifts=UserGifts::where('user_id',$id) ->orderBy('id', 'desc')->get();
     
     
        return $this->returnData('UserGifts',$UserGifts);

        }
        public function DeleteUserGifts($id){
    
            $UserGifts=UserGifts::where('id',$id)->delete();
         if($UserGifts){
            return $UserGifts;
         }else{
            return $this->returnError('E001', 'Can\'t delete UserGifts');
         }
         
            
    
            }

        
}
