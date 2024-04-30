<?php

namespace App\Http\Controllers;

use Validator;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\gift;
use App\Models\RoomGifts;
use App\Models\UserApp;
use  App\Events\RoomEvent;
use App\Models\Chairs;
use App\Models\Rooms;
use App\Models\RoomKarisma;
use App\Models\Agency;
use App\Models\LuckyGiftsTrack;
use App\Models\GiftsTarking;
use App\Models\Leaderboard;
use  App\Events\UserEvent;
use  App\Events\glopel;
use App\Models\Families;
use App\Models\games;
use App\Models\insult;

class GiftController extends Controller
{
    use GeneralTrait;




    public function  sentGift2(Request $request)
    {

        try {

            $rules = [
                "Listuser" => "required",
                "gift_id" => "required",
                "room_id" => "required|exists:rooms,id",
                "user_id" => "required",
                "quantity" => "required",
                "Cost" => "required",
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($request->quantity < 0) {
                return 'asd';
            }
            if ($request->quantity != 1 && $request->quantity != 5 && $request->quantity != 10 && $request->quantity != 20) {
                return 'asd';
            }

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $explode_id = json_decode($request->Listuser, true);
            $gift = gift::where('id', $request->gift_id)->first();
            $usernum = count($explode_id);

            $maincost = $usernum * $request->quantity * $gift->price;


            $room = Rooms::find($request->room_id);

            $users = UserApp::where('id', $request->user_id)->first();

            if ($users->coins < $maincost || $maincost < 0 || $request->Cost < 0 || $users->coins < 0) {

                return 'dont have enough coins';
            }
            if ($users->FamilyId != null) {
                $users->increment('FamilyKarisma', $maincost);
                $family = Families::find($users->FamilyId)->increment('Karisma', $maincost);
                $Leaderboard = Leaderboard::create([
                    'user_id' => $request->user_id,
                    'family_id' => $users->FamilyId,
                    'status' => 4,
                    'coins' => $maincost,
                    'gift_id' => $request->gift_id,
                    'event_id' => $gift->event_id

                ]);
            }

            $Roomkarismas = RoomKarisma::create([
                'user_id' => $request->user_id,
                'room_id' => $request->room_id,
                'karisma' => $gift->price * $request->quantity,
            ]);

            $Leaderboard = Leaderboard::create([
                'room_id' => $request->room_id,
                'status' => 3,
                'coins' => $maincost,
                'gift_id' => $request->gift_id,
                'event_id' => $gift->event_id
            ]);
            $Leaderboard = Leaderboard::create([
                'user_id' => $request->user_id,
                'status' => 1,
                'coins' => $maincost,
                'gift_id' => $request->gift_id,
                'event_id' => $gift->event_id,
                'agency_id' => $users->AgencyId

            ]);
            $explode_id = json_decode($request->Listuser, true);
                $usernames=[];
            foreach ($explode_id as $items) {
                
                $user = UserApp::find($items);
                   $usernames[]= $user->name;
                $user->increment('Input', (($gift->price * $request->quantity) / 100) * 100);

                $user->increment('ginput', (($gift->price * $request->quantity) / 100) * 100);



                $Leaderboard = Leaderboard::create([
                    'user_id' => $items,
                    'status' => 2,
                    'coins' => (($gift->price * $request->quantity) / 100) * 100,
                    'gift_id' => $request->gift_id,
                    'agency_id' => $user->AgencyId,
                    'event_id' => $gift->event_id

                ]);
                $GiftsTarking = GiftsTarking::create([
                    'sender_id' => $request->user_id,
                    'reciver_id' => $user->id,
                    'gift_id' => $gift->id,
                    'room_id' => $request->room_id,
                    'karisma' => $request->quantity * $gift->price,
                    'lucky' => 0,
                ]);
                if ($user->AgencyId != null) {
                    $user->increment('AgencyKarisma', (($gift->price * $request->quantity) / 100) * 100);
                }
                $chairs = Chairs::where([['user_id', $items], ['room_id', $request->room_id]])->first();
                if ($chairs != null) {
                    $chairs->increment('Karisma', $gift->price * $request->quantity);
                }




                $RoomGifts = RoomGifts::where([['user_id', $items], ['gift_id', $request->gift_id], ['state', 1]])->first();
                if ($RoomGifts == null || $RoomGifts == []) {
                    $RoomGifts = RoomGifts::create([
                        'user_id' => $items,
                        'gift_id' => $request->gift_id,
                        'room_id' => $request->room_id,
                        'quantity' => $request->quantity,
                        'state' => 1,
                    ]);
                } else {
                    $RoomGifts->quantity = $RoomGifts->quantity + 1;
                    $RoomGifts->save();
                }
            }

            $users->decrement('coins', $maincost);
            $users->increment('Karisma', $maincost);

            $room->increment('Karisma', $maincost);
            $agency = Agency::where('user_id', $room->admin_id)->first();


            // $roomadmin = UserApp::where('id', $room->admin_id)->first();

            // $roomadmin->increment('coins', ($maincost / 100) * 3);
            // if ($roomadmin->AgencyId != null) {
            //     $roomadmin->increment('AgencyKarisma', ($maincost / 100) * 3);
            // }
            $RoomGifts = RoomGifts::where([['user_id', $request->user_id], ['gift_id', $request->gift_id], ['state', 0]])->first();

            $gift->Listuser = $request->Listuser;

            if ($RoomGifts == null || $RoomGifts == []) {

                $RoomGifts = RoomGifts::create([
                    'user_id' => $request->user_id,
                    'gift_id' => $request->gift_id,
                    'room_id' => $request->room_id,
                    'quantity' => $request->quantity,
                    'state' => 0,
                ]);
            } else {
                $RoomGifts->quantity = $RoomGifts->quantity + 1;
                $RoomGifts->save();
            }


            array_add($RoomGifts, 'lsit', $request->Listuser);
            $rr = array_add($RoomGifts, 'gift', $gift);
            $gift->quantity = $request->quantity;
            if ($RoomGifts) {
                if ($gift->price >= 2000) {
                    if (count($explode_id) == 1) {
                        $Reciver = UserApp::find($explode_id[0]);
                        event(new glopel(0, ['Sender' => ['image' => $users->image, 'id' => $users->id, 'name' => $users->name, 'Hidden' => $users->Hidden, 'Karisma' => $users->Karisma], 'Reciver' => ['image' => $Reciver->image, 'id' => $Reciver->id, 'name' => $Reciver->name, 'Hidden' => $Reciver->Hidden, 'Karisma' => $Reciver->Karisma], 'gift' => $gift, 'Quantati' => $request->quantity, 'Roomid' => $request->room_id, 'Room_name' => $room->name]));
                    } else {
                        event(new glopel(1, ['Sender' => ['image' => $users->image, 'id' => $users->id, 'name' => $users->name, 'Hidden' => $users->Hidden, 'Karisma' => $users->Karisma], 'Reciver' => $user, 'gift' => $gift, 'Quantati' => $request->quantity, 'Roomid' => $request->room_id, 'Room_name' => $room->name]));
                    }
                }
            event(new RoomEvent(29,['gift'=>$gift,'user'=>$usernames,'usersender'=>$users],$request->room_id));
                return ['gift' => $gift, 'user' => $users];
            } else {
                return $this->returnError('215', 'Can\'t sent gifts');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function  Getdice()
    {
        $image = '';
        $Random = mt_rand(1, 6);
        if ($Random == 1) {
            $image = 'one.mp4.lottie.json';
        } else if ($Random == 2) {
            $image = 'two.mp4.lottie.json';
        } else if ($Random == 3) {
            $image = 'three.mp4.lottie.json';
        } else if ($Random == 4) {
            $image = 'four.mp4.lottie.json';
        } else if ($Random == 5) {
            $image = 'five.mp4.lottie.json';
        } else if ($Random == 6) {
            $image = 'six.mp4.lottie.json';
        } else {
            $image = 'one.mp4.lottie.json';
        }

        return  $image;
        //echo ($items[array_rand($items)]*$cost)/100   ;
    }
    public function Playdice(Request $request)
    {
        try {
            $rules = [
                "user_id" => "required|unique:gifts,name",
                "room_id" => "required",

            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $users = UserApp::where('id', $request->user_id)->first();
            $IMAGE = $this->Getdice();

            event(new RoomEvent(21, ['user' => $users, 'dice' => $IMAGE], $request->room_id));
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function Playrollet(Request $request)
    {
        try {
            $rules = [
                "user_id" => "required|unique:gifts,name",
                "room_id" => "required",
                "name" => "required",

            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $users = UserApp::where('id', $request->user_id)->first();


            event(new RoomEvent(22, ['user' => $users, 'name' => $request->name], $request->room_id));
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function LuckyGiftssum()
    {

        $LuckyGiftsTrack = LuckyGiftsTrack::all()->sum('coins');
        $win = LuckyGiftsTrack::all()->sum('wincoins');
        return   ['coins' => $LuckyGiftsTrack, 'win' => $win];
    }
    public function LuckyGiftsTrack(Request $request)
    {
        try {

            $LuckyGiftsTrack = LuckyGiftsTrack::create([
                'user_id' => 1,
                'coins' => 200,
                'percentage' => 10,
                'wincoins' => 1000,
                'quantity' => 5,
                'gift_id' => 4,
            ]);
            if ($LuckyGiftsTrack) {
                return $this->returnData('gift', $LuckyGiftsTrack);
            } else {
                return $this->returnError('E001', 'Can\'t add $LuckyGiftsTrack');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //--------------------------------------RemoveGift
    public function RemoveGift(Request $request)
    {
        try {
            $rules = [
                "id" => "required|exists:gifts,id",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }


            $gift = gift::where('id', $request->id)->delete();

            if ($gift) {
                return $this->returnData('gift', $gift);
            } else {
                return $this->returnError('E001', 'Can\'t remove gifts');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //--------------------------------------UpdateGift
    public function UpdateGift(Request $request)
    {
        try {
            $rules = [
                "id" => "required|exists:gifts,id",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }


            $image = '';
            $svgimage = '';
            $sound = '';
            $ShopItem = gift::find($request->id);
            if ($request->hasfile('image')) {
                $fileName = time() . '.png';
                $file1 = $request->image->move(public_path('images'), $fileName);
                $ShopItem->image = $fileName;

                $ShopItem->save();
            }
            if ($request->hasfile('svga')) {
                $fileName = time() . '.svg';
                $file1 = $request->svga->move(public_path('images'), $fileName);
                $ShopItem->svga = $fileName;
                $ShopItem->save();
            }
            if ($request->hasfile('sound')) {
                $fileName = time() . '.mp3';
                $file1 = $request->sound->move(public_path('images'), $fileName);
                $ShopItem->sound = $fileName;

                $ShopItem->save();
            }

            $gift = tap(gift::find($request->id))->update($request->except(['sound', 'image', 'svga']))->first();

            if ($gift) {
                return $this->returnData('gifts', gift::find($request->id));
            } else {
                return $this->returnError('E001', 'Can\'t update gifts');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //---------------------------------------------------
    //0 i sent

    public function  sentGift(Request $request)
    {

        try {

            $rules = [
                "Listuser" => "required",
                "gift_id" => "required",
                "room_id" => "required|exists:rooms,id",
                "user_id" => "required",
                "quantity" => "required",
                "Cost" => "required",
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($request->quantity < 0) {
                return 'asd';
            }
            if ($request->quantity != 1 && $request->quantity != 5 && $request->quantity != 10 && $request->quantity != 20) {
                return 'asd';
            }

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $explode_id = json_decode($request->Listuser, true);
            $gift = gift::where('id', $request->gift_id)->first();
            $usernum = count($explode_id);

            $maincost = $usernum * $request->quantity * $gift->price;


            $room = Rooms::find($request->room_id);

            $users = UserApp::where('id', $request->user_id)->first();

            if ($users->coins < $maincost || $maincost < 0 || $request->Cost < 0 || $users->coins < 0) {

                return 'dont have enough coins';
            }
            if ($users->FamilyId != null) {
                $users->increment('FamilyKarisma', $maincost);
                $family = Families::find($users->FamilyId)->increment('Karisma', $maincost);
                $Leaderboard = Leaderboard::create([
                    'user_id' => $request->user_id,
                    'family_id' => $users->FamilyId,
                    'status' => 4,
                    'coins' => $maincost,
                    'gift_id' => $request->gift_id,
                    'event_id' => $gift->event_id

                ]);
            }

            $Roomkarismas = RoomKarisma::create([
                'user_id' => $request->user_id,
                'room_id' => $request->room_id,
                'karisma' => $gift->price * $request->quantity,
            ]);

            $Leaderboard = Leaderboard::create([
                'room_id' => $request->room_id,
                'status' => 3,
                'coins' => $maincost,
                'gift_id' => $request->gift_id,
                'event_id' => $gift->event_id
            ]);
            $Leaderboard = Leaderboard::create([
                'user_id' => $request->user_id,
                'status' => 1,
                'coins' => $maincost,
                'gift_id' => $request->gift_id,
                'event_id' => $gift->event_id,
                'agency_id' => $users->AgencyId

            ]);
            $explode_id = json_decode($request->Listuser, true);
            foreach ($explode_id as $items) {
                $user = UserApp::find($items);
                $user->increment('Input', (($gift->price * $request->quantity) / 100) * 100);

                $user->increment('ginput', (($gift->price * $request->quantity) / 100) * 100);



                $Leaderboard = Leaderboard::create([
                    'user_id' => $items,
                    'status' => 2,
                    'coins' => (($gift->price * $request->quantity) / 100) * 100,
                    'gift_id' => $request->gift_id,
                    'agency_id' => $user->AgencyId,
                    'event_id' => $gift->event_id

                ]);
                $GiftsTarking = GiftsTarking::create([
                    'sender_id' => $request->user_id,
                    'reciver_id' => $user->id,
                    'gift_id' => $gift->id,
                    'room_id' => $request->room_id,
                    'karisma' => $request->quantity * $gift->price,
                    'lucky' => 0,
                ]);
                if ($user->AgencyId != null) {
                    $user->increment('AgencyKarisma', (($gift->price * $request->quantity) / 100) * 100);
                }
                $chairs = Chairs::where([['user_id', $items], ['room_id', $request->room_id]])->first();
                if ($chairs != null) {
                    $chairs->increment('Karisma', $gift->price * $request->quantity);
                }




                $RoomGifts = RoomGifts::where([['user_id', $items], ['gift_id', $request->gift_id], ['state', 1]])->first();
                if ($RoomGifts == null || $RoomGifts == []) {
                    $RoomGifts = RoomGifts::create([
                        'user_id' => $items,
                        'gift_id' => $request->gift_id,
                        'room_id' => $request->room_id,
                        'quantity' => $request->quantity,
                        'state' => 1,
                    ]);
                } else {
                    $RoomGifts->quantity = $RoomGifts->quantity + 1;
                    $RoomGifts->save();
                }
            }

            $users->decrement('coins', $maincost);
            $users->increment('Karisma', $maincost);

            $room->increment('Karisma', $maincost);
            $agency = Agency::where('user_id', $room->admin_id)->first();


            // $roomadmin = UserApp::where('id', $room->admin_id)->first();

            // $roomadmin->increment('coins', ($maincost / 100) * 3);
            // if ($roomadmin->AgencyId != null) {
            //     $roomadmin->increment('AgencyKarisma', ($maincost / 100) * 3);
            // }
            $RoomGifts = RoomGifts::where([['user_id', $request->user_id], ['gift_id', $request->gift_id], ['state', 0]])->first();

            $gift->Listuser = $request->Listuser;

            if ($RoomGifts == null || $RoomGifts == []) {

                $RoomGifts = RoomGifts::create([
                    'user_id' => $request->user_id,
                    'gift_id' => $request->gift_id,
                    'room_id' => $request->room_id,
                    'quantity' => $request->quantity,
                    'state' => 0,
                ]);
            } else {
                $RoomGifts->quantity = $RoomGifts->quantity + 1;
                $RoomGifts->save();
            }


            array_add($RoomGifts, 'lsit', $request->Listuser);
            $rr = array_add($RoomGifts, 'gift', $gift);
            $gift->quantity = $request->quantity;
            if ($RoomGifts) {
                if ($gift->price >= 2000) {
                    if (count($explode_id) == 1) {
                        $Reciver = UserApp::find($explode_id[0]);
                        event(new glopel(0, ['Sender' => ['image' => $users->image, 'id' => $users->id, 'name' => $users->name, 'Hidden' => $users->Hidden, 'Karisma' => $users->Karisma], 'Reciver' => ['image' => $Reciver->image, 'id' => $Reciver->id, 'name' => $Reciver->name, 'Hidden' => $Reciver->Hidden, 'Karisma' => $Reciver->Karisma], 'gift' => $gift, 'Quantati' => $request->quantity, 'Roomid' => $request->room_id, 'Room_name' => $room->name]));
                    } else {
                        event(new glopel(1, ['Sender' => ['image' => $users->image, 'id' => $users->id, 'name' => $users->name, 'Hidden' => $users->Hidden, 'Karisma' => $users->Karisma], 'Reciver' => $user, 'gift' => $gift, 'Quantati' => $request->quantity, 'Roomid' => $request->room_id, 'Room_name' => $room->name]));
                    }
                }
                event(new RoomEvent(5, ['gift' => $gift, 'user' => $users], $request->room_id));
                return ['gift' => $gift, 'user' => $users];
            } else {
                return $this->returnError('215', 'Can\'t sent gifts');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


    //-----------------------------------



public function  GetPersantage($cost,$id){
        
         $game=games::where('id',2)->first();
        
         $xxxxx=0;
    
         $game->increment('usersnumber',$cost);
         if($game->usersnumber>10000){
                       $xxxxx=$this->momentss($cost,$game->usersnumber);

         }
          
          if( $xxxxx==0){
              $game->increment('usersnumber',0);

          }else{
              $game->decrement('usersnumber',$xxxxx*$cost);
          }
                  
                       return ['win'=> $xxxxx*$cost, 'Persantage'=>$xxxxx, 'Randomnumber'=> $xxxxx ];
        
        

    //     $result = $this->randomChoice($numbers, $weights);
    
    //         if($game->usersnumber<=100){
                   
    //           $game->increment('usersnumber',$cost);
                 
    //                 return ['win'=>0, 'Persantage'=>0, 'Randomnumber'=>0 ];
    //         }
    //         else
    //         {
                  

    // if($result*$cost>$game->usersnumber){
    
    //     $xxxxx=$this->momentss($cost,$game->usersnumber);
      
    //           $PRSENTAGEP= $xxxxx;
           
                    
    //               $user->decrement('luckyamount',$xxxxx*$cost);
    //                   return ['win'=> $xxxxx*$cost, 'Persantage'=>$xxxxx, 'Randomnumber'=> $xxxxx ];

    // }else{
            
    //          $user->decrement('luckyamount',$result*$cost);
    //      return ['win'=> $result*$cost, 'Persantage'=> $result, 'Randomnumber'=>0 ];

    // }
                
                
                
    //         }
                
      
    
  
       
           //echo ($items[array_rand($items)]*$cost)/100   ;
       }
       
    public   function randomChoice($arr, $weights)
    {
        $randIndex = mt_rand(0, array_sum($weights) - 1);
        $sum = 0;

        for ($i = 0; $i < count($arr); $i++) {
            $sum += $weights[$i];
            if ($randIndex < $sum) {
                return $arr[$i];
            }
        }
    }
       
        public function  momentss($num,$max){
     $numbers = [5,10,15,20,30,35,40,50,100];
 
 

$valid_nums = array_filter($numbers,  function($n) use ($num,$max) {
    return $n * $num <=  $max;
});

if (count($valid_nums)) {
    $rand_num = $valid_nums[array_rand($valid_nums)];
} else {
    $rand_num = 0;
}

return   $rand_num;
 }
       
public function  SentLuckyGift(Request $request){
  
    
    try {
        $rules = [
            "Listuser" => "required",
            "gift_id" => "required",
            "room_id" => "required|exists:rooms,id",
            "user_id" => "required",
            "quantity"=>"required",
            "Cost"=>"required",
         ];
        $validator = Validator::make($request->all(), $rules);
if($request->quantity<0){
    return 'asd';
}
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
         $explode_id = json_decode($request->Listuser, true);
          $gift=gift::find($request->gift_id);
         $usernum= count(   $explode_id );
         $maincost= $usernum*$request->quantity*$gift->price;
 $user=UserApp::find($request->user_id);
$CurrentCoins= $user->coins;
$user->update(['social'=>$request->Cost]);
 if((int) $user->coins<(int)$request->Cost||(int) $user->coins<$maincost){
     
     return 'dont have enough coins';
 }
  if($user->FamilyId!=null){
        $user->increment('FamilyKarisma',$maincost);
        $family=Families::find($user->FamilyId)->increment('Karisma',($gift->price/100)*10);
        
 }
    $Roomkarisma=RoomKarisma::where([['user_id',$request->user_id],['room_id',$request->room_id]])->first();
 
     $Roomkarismas = RoomKarisma::create([
                  'user_id'=> $request->user_id,
                  'room_id'=> $request->room_id,
                  'karisma'=>($gift->price/100)*10
                ]);
  

    $Leaderboard = Leaderboard::create([
                'room_id'=> $request->room_id,
                'status'=> 3,
                'coins'=> $maincost,
                'gift_id'=>$request->gift_id,
                'event_id'=>$gift->event_id
             
            ]);
        $Leaderboard = Leaderboard::create([
                'user_id'=> $request->user_id,
                'status'=> 1,
                'coins'=>  $maincost,
                'gift_id'=>$request->gift_id,
                'event_id'=>$gift->event_id
            ]);
   $usernames=[];
        foreach ($explode_id as $items) {
             $users=UserApp::find($items);
                   $usernames[]= $users->name;
             $users->increment('Input',(($gift->price)/100)*10);
         $users->increment('ginput',(($gift->price)/100)*10);
             $Leaderboard = Leaderboard::create([
                'user_id'=> $items,
                'status'=> 2,
                'coins'=>  $gift->price/10,
                'gift_id'=>$request->gift_id,
                'event_id'=>$gift->event_id
             
            ]);
          $GiftsTarking = GiftsTarking::create([
            'sender_id'=> $request->user_id,
            'reciver_id'=>$users->id,
            'gift_id'=>$gift->id,
            'room_id'=>$request->room_id,
            'karisma'=> $maincost,
            'lucky'=>1,
            ]);
             if($users->AgencyId!=null){
             $users->increment('AgencyKarisma',(($gift->price)/100)*10);
             }
            
             $Chairuser=Chairs::where([['user_id',$items],['room_id',$request->room_id]])->first();
             if($Chairuser!=null){
                 $Chairuser->increment('Karisma',(($gift->price)/100)*10);
             }
             
            
             $RoomGifts=RoomGifts::where([['user_id',$items],['gift_id',$request->gift_id],['state',1]])->first(); 
             if($RoomGifts==null||$RoomGifts==[]){
                $RoomGifts = RoomGifts::create([
                    'user_id'=> $items,
                    'gift_id'=> $request->gift_id,
                    'room_id'=> $request->room_id,
                    'quantity'=> $request->quantity,
                    'state'=>1,
                ]);
             }
             else{
                $RoomGifts->quantity=$RoomGifts->quantity +1;
                $RoomGifts->save();
             }
        }
           if($maincost> $user->coins){
              
                $insult = insult::create([
            'user_id'=>$user->id ,
            'type'=>'lucky',
            'text'=>$maincost,
        ]);
        }else{
            
            if($user->coins<$maincost){
                  return $this->returnError('215', 'Can\'t sent gifts');
          
            }else{
                  $user->decrement('coins', $maincost);
            }
                 

               
        }
        
      
         $user->increment('Karisma', $maincost);
      $room =Rooms::find($request->room_id);
      $room->increment('Karisma', $maincost);
       
        


    $ReturnedValue=$this->GetPersantage((($maincost)/100)*90, $user->id);
      
    if($ReturnedValue['Persantage']>=90){
         event(new glopel(4,['sender'=>['image'=>$user->image,'name'=>$user->name],'wincoins'=>$ReturnedValue['win'],'gift'=>$gift->name,'winx'=>$ReturnedValue['Persantage'],'Roomid'=>$request->room_id,'Room_name'=>$room->name]));
    }
    
      $user->increment('coins',(int)$ReturnedValue['win']);
     
        $RoomGifts=RoomGifts::where([['user_id',$request->user_id],['gift_id',$request->gift_id],['state',0]])->first(); 
 
        $gift->Listuser=$request->Listuser;
        
        if($RoomGifts==null||$RoomGifts==[]){
            
            $RoomGifts = RoomGifts::create([
                'user_id'=> $request->user_id,
                'gift_id'=> $request->gift_id,
                'room_id'=> $request->room_id,
                'quantity'=> $request->quantity,
                'state'=>0,
            ]);
        }else{
            $RoomGifts->quantity=$RoomGifts->quantity+1;
            $RoomGifts->save();
        }
     
  
      array_add($RoomGifts,'lsit',$request->Listuser);
      $rr= array_add($RoomGifts,'gift',$gift);
      $gift->quantity=$request->quantity;
        if($RoomGifts){
            $LuckyGiftsTrack = LuckyGiftsTrack::create([
            'user_id'=> $request->user_id,
            'coins'=>$request->Cost,
            'percentage'=>$ReturnedValue['Persantage'],
            'wincoins'=>$ReturnedValue['win'],
            'quantity'=>$request->quantity,
            'gift_id'=>$request->gift_id,
            'usercoins'=>$user->coins,
            'beforeusercoins'=>$CurrentCoins,
        ]);
            if($ReturnedValue['win']>=1000){
                  $Reciver=UserApp::find($explode_id[0]);
                 event(new glopel(2,['Sender'=>$user,'Reciver'=>$Reciver,'gift'=>$gift,'Quantati'=>$ReturnedValue['win'],'Roomid'=>$request->room_id,'Room_name'=>$room->name]));
          
            
          }
                        event(new RoomEvent(29,['gift'=>$gift,'user'=>$usernames,'usersender'=>$user,'kind'=>1],$request->room_id));

        //    event(new RoomEvent(5,['gift'=>$gift,'user'=> $user],$request->room_id));
             return $this->returnData('gain',['ReturnedValue'=>$ReturnedValue,'coins'=>$user->coins]); 
     

          } else
          {
            return $this->returnError('215', 'Can\'t sent gifts');
        }
 
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}

//
 public function  SentCompo(Request $request){
    try {
        $rules = [
            "Listuser" => "required",
            "gift_id" => "required",
            "room_id" => "required|exists:rooms,id",
            "user_id" => "required",
            "quantity"=>"required",
            "Cost"=>"required",
         ];
        $validator = Validator::make($request->all(), $rules);
if($request->quantity<0){
    return 'asd';
}
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
         $explode_id = json_decode($request->Listuser, true);
          $gift=gift::find($request->gift_id);
         $usernum= count(   $explode_id );
         $maincost= $usernum*$request->quantity*$gift->price;
 $user=UserApp::find($request->user_id);
$CurrentCoins= $user->coins;
$user->update(['social'=>$request->Cost]);
 if((int) $user->coins<(int)$request->Cost||(int) $user->coins<$maincost){
     
     return 'dont have enough coins';
 }
  if($user->FamilyId!=null){
        $user->increment('FamilyKarisma',$maincost);
        $family=Families::find($user->FamilyId)->increment('Karisma',($gift->price*$request->quantity)/10);
        
 }
    $Roomkarisma=RoomKarisma::where([['user_id',$request->user_id],['room_id',$request->room_id]])->first();
 
     $Roomkarismas = RoomKarisma::create([
                  'user_id'=> $request->user_id,
                  'room_id'=> $request->room_id,
                  'karisma'=>( $gift->price*$request->quantity)/10,
                ]);
  

    $Leaderboard = Leaderboard::create([
                'room_id'=> $request->room_id,
                'status'=> 3,
                'coins'=> $maincost,
                'gift_id'=>$request->gift_id,
                'event_id'=>$gift->event_id
             
            ]);
        $Leaderboard = Leaderboard::create([
                'user_id'=> $request->user_id,
                'status'=> 1,
                'coins'=>  $maincost,
                'gift_id'=>$request->gift_id,
                'event_id'=>$gift->event_id
            ]);
 
     $usernames=[];
        foreach ($explode_id as $items) {
             $users=UserApp::find($items);
                   $usernames[]= $users->name;
                
             $users->increment('Input', (($gift->price)/100)*10);
           $users->increment('ginput',(($gift->price)/100)*10);
             $Leaderboard = Leaderboard::create([
                'user_id'=> $items,
                'status'=> 2,
                'coins'=>  $gift->price/10,
                'gift_id'=>$request->gift_id,
                'event_id'=>$gift->event_id
             
            ]);
          $GiftsTarking = GiftsTarking::create([
            'sender_id'=> $request->user_id,
            'reciver_id'=>$users->id,
            'gift_id'=>$gift->id,
            'room_id'=>$request->room_id,
            'karisma'=> $maincost,
            'lucky'=>1,
            ]);
             if($users->AgencyId!=null){
             $users->increment('AgencyKarisma', (($gift->price)/100)*10);
             }
            
             $Chairuser=Chairs::where([['user_id',$items],['room_id',$request->room_id]])->first();
             if($Chairuser!=null){
                 $Chairuser->increment('Karisma',$gift->price/10);
             }
            
        }
        
         
          if($maincost> $user->coins){
          
                $insult = insult::create([
            'user_id'=>$user->id ,
            'type'=>'compo',
            'text'=>$maincost,
        ]);
        }else{
            
            
                if($user->coins<$maincost){
                  return $this->returnError('215', 'Can\'t sent gifts');
          
            }else{
                  $user->decrement('coins', $maincost);
            }
        }
        
         $user->increment('Karisma', $maincost);
      $room =Rooms::find($request->room_id);
      $room->increment('Karisma', $maincost);
       
        


    $ReturnedValue=$this->GetPersantage((($maincost)/100)*90, $user->id);
     if($ReturnedValue['Persantage']>=90){
         event(new glopel(4,['sender'=>['image'=>$user->image,'name'=>$user->name],'wincoins'=>$ReturnedValue['win'],'gift'=>$gift->name,'winx'=>$ReturnedValue['Persantage'],'Roomid'=>$request->room_id,'Room_name'=>$room->name]));
    }
    
      $user->increment('coins',(int)$ReturnedValue['win']);
     
        $RoomGifts=RoomGifts::where([['user_id',$request->user_id],['gift_id',$request->gift_id],['state',0]])->first(); 
 
        $gift->Listuser=$request->Listuser;
        
        if($RoomGifts==null||$RoomGifts==[]){
            
            $RoomGifts = RoomGifts::create([
                'user_id'=> $request->user_id,
                'gift_id'=> $request->gift_id,
                'room_id'=> $request->room_id,
                'quantity'=> $request->quantity,
                'state'=>0,
            ]);
        }else{
            $RoomGifts->quantity=$RoomGifts->quantity+1;
            $RoomGifts->save();
        }
     
  
      array_add($RoomGifts,'lsit',$request->Listuser);
      $rr= array_add($RoomGifts,'gift',$gift);
      $gift->quantity=$request->quantity;
        if($RoomGifts){
            $LuckyGiftsTrack = LuckyGiftsTrack::create([
            'user_id'=> $request->user_id,
            'coins'=>$request->Cost,
            'percentage'=>$ReturnedValue['Persantage'],
            'wincoins'=>$ReturnedValue['win'],
            'quantity'=>$request->quantity,
            'gift_id'=>$request->gift_id,
            'usercoins'=>$user->coins,
            'beforeusercoins'=>$CurrentCoins,
        ]);
            if($ReturnedValue['win']>=1000){
                  $Reciver=UserApp::find($explode_id[0]);
                 event(new glopel(2,['Sender'=>$user,'Reciver'=>$Reciver,'gift'=>$gift,'Quantati'=>$ReturnedValue['win'],'Roomid'=>$request->room_id,'Room_name'=>$room->name]));
          
            
          }
            
                   event(new RoomEvent(25,['gift'=>$gift,'user'=>$usernames,'usersender'=>$user,'kind'=>1],$request->room_id));
            // event(new RoomEvent(25,['gift'=>$gift,'user'=> $user,'kind'=>1],$request->room_id));
             return $this->returnData('gain',['ReturnedValue'=>$ReturnedValue,'coins'=>$user->coins]); 
     

          } else
          {
            return $this->returnError('215', 'Can\'t sent gifts');
        }
 
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
}
    //-----------------------------------
    public function  GetRoomGift(Request $request)
    {
        try {

            $rules = [
                "room_id" => "required|exists:rooms,id",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $RoomGifts = RoomGifts::where([['room_id', $request->room_id], ['state', 0]])->with('gift')->get();
            return $this->returnData('gifts', $RoomGifts);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //-----------------------------------
    public function AddGift(Request $request)
    {
        try {
            $rules = [
                "name" => "required|unique:gifts,name",
                "image" => "required",
                "category_id" => "required|exists:gift_categories,id",
                "price" => 'required',
                "svga" => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $image = '';
            if ($request->hasfile('image')) {
                $fileName = time() . '.png';
                $file1 = $request->image->move(public_path('images'), $fileName);
                $image = $fileName;
            }
            $svga = '';
            if ($request->hasfile('svga')) {
                $fileName = time() . '15' . '.svga';
                $file1 = $request->svga->move(public_path('images'), $fileName);
                $svga = $fileName;
            }

            $sound = '';
            if ($request->hasfile('sound')) {
                $fileName = time() . '.mp3';
                $file1 = $request->sound->move(public_path('images'), $fileName);
                $sound = $fileName;
            }
            $gift = gift::create([
                'name' => $request->name,
                'image' => $image,
                'sound' => $sound,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'svga' => $svga,
            ]);
            if ($gift) {
                return $this->returnData('gift', $gift);
            } else {
                return $this->returnError('E001', 'Can\'t add gift');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
