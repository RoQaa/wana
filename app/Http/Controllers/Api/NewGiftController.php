<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
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
use Illuminate\Support\Facades\DB;

class NewGiftController extends Controller
{
    use GeneralTrait;

    public function  NewSentGift(Request $request)
    {
        try {
            $rules = [
                "Listuser" => "required",
                "gift_id" => "required",
                "room_id" => "required|exists:rooms,id",
                "user_id" => "required|exists:user_apps,id",
                "quantity" => "required|in:1,5,10,20",
                "Cost" => "required",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $explode_id = json_decode($request->Listuser, true);
             $gift = DB::table('gifts')->where("id",$request->gift_id)->first();
            $usernum = count($explode_id);

            $maincost = $usernum * $request->quantity * $gift->price;


            $room = Rooms::find($request->room_id);
            // $room = DB::table('rooms')->where("id",$request->room_id)->first();


            $users = UserApp::where('id', $request->user_id)->first();

            if ($users->coins < $maincost || $maincost < 0 || $request->Cost < 0 || $users->coins < 0) {

                return 'dont have enough coins';
            }
            if ($users->FamilyId != null) {
                Leaderboard::create([
                    'user_id' => $request->user_id,
                    'family_id' => $users->FamilyId,
                    'status' => 4,
                    'coins' => $maincost,
                    'gift_id' => $request->gift_id,
                    'event_id' => $gift->event_id

                ]);
            }
             RoomKarisma::create([
                'user_id' => $request->user_id,
                'room_id' => $request->room_id,
                'karisma' => $gift->price * $request->quantity,
            ]);

             Leaderboard::create([
                'room_id' => $request->room_id,
                'status' => 3,
                'coins' => $maincost,
                'gift_id' => $request->gift_id,
                'event_id' => $gift->event_id
            ]);
             Leaderboard::create([
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
                    $multi = (($gift->price * $request->quantity) / 100) * 100;
                    $user->increment('Input',  $multi);
                    $user->increment('ginput',  $multi);

                 Leaderboard::create([
                    'user_id' => $items,
                    'status' => 2,
                    'coins' => $multi,
                    'gift_id' => $request->gift_id,
                    'agency_id' => $user->AgencyId,
                    'event_id' => $gift->event_id

                ]);
                GiftsTarking::create([
                    'sender_id' => $request->user_id,
                    'reciver_id' => $user->id,
                    'gift_id' => $gift->id,
                    'room_id' => $request->room_id,
                    'karisma' => $request->quantity * $gift->price,
                    'lucky' => 0,
                ]);
                if ($user->AgencyId != null) {
                    $user->increment('AgencyKarisma',$multi);
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

            $roomadmin = UserApp::where('id', $room->admin_id)->first();

            $roomadmin->increment('coins', ($maincost / 100) * 3);
            if ($roomadmin->AgencyId != null) {
                $roomadmin->increment('AgencyKarisma', ($maincost / 100) * 3);
            }
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

}
