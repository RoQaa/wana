<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\UserApp;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\LevelGifts;
use App\Models\LevelFrames;
class LevelGiftsController extends Controller
{
    use GeneralTrait;
    public function AddLevelGifts(Request $request){
        try {
       
           $LevelGifts = LevelGifts::create([
            "user_id"=>  $request->user_id,
            "svga"=>  $request->svga,
            "tittle" =>  $request->tittle,
            "message" =>  $request->message,
            "image" =>  $request->image,
            "kind" =>  $request->kind,
        ]);
       
        if($LevelGifts){
            return $this->returnData('LevelGifts',$LevelGifts);
            }else{
                return $this->returnError('E001', 'Can\'t add LevelGifts');
         }  
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function GetMyLevelGifts($id){
        $user = UserApp::where('id',$id)->first();
        $Karisma=floor ($user->Karisma/10000)+1;
      //  return floor ($user->Karisma/10000)+1;
$rwer=$this->checkLevel(floor ($user->Karisma/10000)+1);
  $levelframes=LevelFrames::all();
  for($i = 0;$i<=count($levelframes)-1;$i++)
  {
       if($Karisma>=$levelframes[$i]->level_start&&$Karisma<=$levelframes[$i]->level_end){
         $LevelGiftss=LevelGifts::where([['user_id',$id],['image',$levelframes[$i]->image]])->first();

        if($LevelGiftss!=null){
 
        }else{
            $LevelGiftss = LevelGifts::create([
                "user_id"=> $id,
                "tittle" => "اطار المستوي",
                "message" => "لقد حصلت علي اطار جديد للمستوي",
                "image" => $levelframes[$i]->image, 
                "svga"=> $levelframes[$i]->svga, 
                "kind" =>  0,
            ]);
        }
       }
  }
  

 

$LevelGifts=LevelGifts::where([['user_id',$id],['image',$rwer]])->first();
 
if($LevelGifts!=null){

}else{
    $LevelGifts = LevelGifts::create([
        "user_id"=> $id,
        "tittle" => "شاره المستوي",
        "message" => "لقد حصلت علي شاره جديده للمستوي جديده",
        "image" =>  $rwer, 
        "kind" =>  1,
    ]);
}

$MyLevelGifts= LevelGifts::where('user_id',$id)  ->orderBy('id', 'desc') ->get();
     
        return $this->returnData('MyLevelGifts',$MyLevelGifts);

        }
        public function checkLevel($Karisma){
            if($Karisma>=10&&$Karisma<=20  ) {
                return   '11.png';
              }else if($Karisma>=21&&$Karisma<=30  ) {
                return  '22.png';
              }else if( $Karisma>=31&&$Karisma<=40  ) {
                return '33.png';
              }else if($Karisma>=41&&$Karisma<=50  ) {
                return '44.png';
              }else if($Karisma>=51&&$Karisma<=60  ) {
                return  '55.png';
              }else if($Karisma>=61&&$Karisma<=70  ) {
                  return  '66.png';
              }else if($Karisma>=71&&$Karisma<=80  ) {
                  return  '77.png';
              }else if($Karisma>=81&&$Karisma<=90  ) {
                  return  '88.png';
              }else if( $Karisma>=91 ) {
                  return  '99.png';
              }else{
                  return  '11.png';
              }

        }
        public function checkLevelEntry($Karisma){
           
            if($Karisma>=10&&$Karisma<=30  ) {
                return   '11.png';
              }else if($Karisma>=31&&$Karisma<=60  ) {
                return  '22.png';
              }else if( $Karisma>=61&&$Karisma<=90  ) {
                return '33.png';
              }else if($Karisma>=91&&$Karisma<=100  ) {
                return '44.png';
              }else if($Karisma>=100 ) {
                return  '55.png';
              }else {

              }

        }

}
