<?php

namespace App\Http\Controllers;
use App\Models\Levels;
use App\Models\LevelPrize;
use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
 use App\Models\UserGifts;
 use Carbon\Carbon;
class LevelsController extends Controller
{
     use GeneralTrait;
    public function GetLevels(){

        try {
       $Levels=Levels::with('frame','entry')->get();
       return $this->returnData('Levels', $Levels );
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }
     
     
       public function SubmitLevelsReward(Request $request){
   try {
        $rules = [
            "level_id" => "required",
            "user_id"=> "required",
           
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $Level=Levels::where('id',$request->level_id)->with('frame','entry')->first();
  
       $prize=LevelPrize::where([['user_id',$request->user_id],['endlevel',$request->level_id]])->first();
       if($prize!=null){
            return $this->returnError('E001', 'Can\'t add LevelPrize');
       }
               $FrameGifts = UserGifts::create([
            "user_id"=>$request->user_id,
            "svga"=>  $Level->entry->svga,
            "title" =>  'هدايا المستوي',
            "message" =>  'لقت تلقيت هديه مستوي جديده',
            "image" => $Level->entry->image,
            "kind" =>  0,
            "created_at"=> Carbon::now()->addDays($Level->entry->days),
        ]);
        
             $EntryGifts = UserGifts::create([
            "user_id"=>$request->user_id,
            "svga"=>  $Level->frame->svga,
            "title" =>  'هدايا المستوي',
            "message" =>  'لقت تلقيت هديه مستوي جديده',
            "image" => $Level->frame->image,
            "kind" =>  1,
            "created_at"=> Carbon::now()->addDays($Level->frame->days),
        ]);
          
        
            $LevelPrize = LevelPrize::create([
            'user_id'=> $request->user_id,
            "endlevel"=> $request->level_id,
        ]);
        
        
        if($LevelPrize){
            return $this->returnData('LevelPrize',$LevelPrize );
            }else{
                return $this->returnError('E001', 'Can\'t add LevelPrize');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
     }
     
        public function Getuserprize($id){
                   $prize=LevelPrize::where('user_id',$id)->get()->pluck('endlevel');
                   return  $prize;

        }
     
}
