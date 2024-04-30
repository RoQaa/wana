<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Relations;
use App\Traits\GeneralTrait;
use Validator;
use App\Models\ShopItem;
use App\Models\UserApp;
class RelationsController extends Controller
{
      use GeneralTrait;

    public function SendRelation(Request $request){
        try {
        $rules = [
            "sender_id" => "required|exists:user_apps,id",
            "user_id" => "required",
            "Relation_id" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        
        $item=ShopItem::where('id',$request->Relation_id)->first();
        if( $item==null){
             return $this->returnError('E001', 'Can\'t add roomcategory');
        }
        $user=UserApp::where('id',$request->sender_id)->first();
        if($user==null||$user->coins<$item->price){
                 return $this->returnError('E001', 'Can\'t add Hack');
        }
     
     
   $chack=  Relations::where([['sender_id',$request->sender_id],['user_id',$request->user_id],['Relation_id',$request->Relation_id],['status','!=',2]])->orwhere([['sender_id',$request->user_id],['user_id',$request->sender_id],['Relation_id',$request->Relation_id],['status','!=',2]])->first();
   
   
     
     if( $chack!=null){
          return $this->returnData('status','Already has relation');
     }
     
     
     
     
           $Relations = Relations::create([
            'sender_id'=> $request->sender_id,
            'user_id'=> $request->user_id,
            'Relation_id'=> $request->Relation_id,
        ]);
        
        $user->decrement('coins',$item->price);
        if( $Relations){
            return $this->returnData('status','done');
            }else{
                return $this->returnError('E001', 'Can\'t add roomcategory');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    
     public function AcceptRelation(Request $request){
           $rules = [
            "user_id" => "required|exists:user_apps,id",
            "id" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        
         $chack=  Relations::where([['user_id',$request->user_id],['id',$request->id],['status',0]])->first();
         if($chack==null){
                 return $this->returnError('E001', 'Can\'t add $chack');
         }
        
          $chack->update(['status'=>1]);
           if( $chack){
            return $this->returnData('status','done');
            }else{
                return $this->returnError('E001', 'Can\'t add roomcategory');
         }  
     }
     
      public function LeaveRelation(Request $request){
           $rules = [
            "user_id" => "required|exists:user_apps,id",
            "id" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        
         $chack=  Relations::where([['id',$request->id],['status',1]])->first();
         if($chack==null){
                 return $this->returnError('E001', 'Can\'t add $chack');
         }
        
          $chack->update(['status'=>2,'Leaved'=>$request->user_id]);
           if( $chack){
            return $this->returnData('status','done');
            }else{
                return $this->returnError('E001', 'Can\'t add roomcategory');
         }  
     }
     
         public function RemoveRelation(Request $request){
           $rules = [
          
            "id" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
      
         $chack=Relations::where([['id',$request->id],['status',0]])->first();
      
         if($chack==null){
                 return $this->returnError('E001', 'Can\'t add roomcategory');
         }
           $item=ShopItem::where('id',$chack->Relation_id)->first();
        if( $item==null){
             return $this->returnError('E001', 'Can\'t add roomcategory');
        }
        $user=UserApp::where('id',$chack->sender_id)->first();
        
          $user->increment('coins',$item->price);
        $chack->delete();
           if( $chack){
            return $this->returnData('status','done');
            }else{
                return $this->returnError('E001', 'Can\'t add roomcategory');
         }  
     }
     
      public function UserRelations($id){
          
          
           $Relations = Relations::where('sender_id',$id)->orwhere('user_id',$id)->with('item:id,name,svggift','user:id,name,image','anotheruser:id,name,image')->get();
           
           return $Relations;
          
      }
        public function UserProfile($id){
          
          
           $Relations = Relations::where([['sender_id',$id],['status',1]])->orwhere([['user_id',$id],['status',1]])->with('item:id,name,svggift','user','anotheruser')->get();
           
           return $Relations;
          
      }
     
     
    
}
