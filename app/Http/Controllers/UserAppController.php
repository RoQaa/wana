<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Validator;
use Auth;
use App\Models\UserApp;
use App\Models\Rooms;
use Illuminate\Support\Facades\Hash;
use File;
use App\Models\baned_devices;
use Illuminate\Support\Str;
use App\Models\Joinroom;
use App\Models\Tournament;
use  App\Events\RoomEvent;
use App\Models\Freinds;
use App\Models\RoomGifts;
use App\Models\Chairs;
use DB;
use App\Models\InboxRoom;
use App\Models\BlockList;
use App\Models\follow;
use  App\Events\UserEvent;
use App\Models\Sales;
use Carbon\Carbon;
use App\Models\Postes;
use App\Models\Visitors;
use App\Models\MyVip;
use App\Models\Agency;
use App\Models\JoinAgencyRequest;
use App\Models\MusicEntry;
use App\Models\insult;
use App\Models\paymentspackages;
use App\Models\StartBanner;
 use App\Models\UserGifts;
 use App\Models\Families_Members;
 use App\Models\Relations;
 use App\Models\LevelPrize;
 use App\Models\payments;
class UserAppController extends Controller
{
    use GeneralTrait;
    
    public function identicatdevice( ){
        // $users = UserApp::groupBy('deviceId')
        //      ->havingRaw('COUNT(*) > 1')
        //      ->get();

// $users = UserApp::groupBy('name')
//             ->havingRaw('COUNT(*) > 1')
//             ->get();
            
  
            
        
    
    // $users = DB::table('user_apps')
    // ->select('deviceId', DB::raw('COUNT(DISTINCT id) AS userCount'), DB::raw('GROUP_CONCAT(DISTINCT CONCAT(name, " (", myappid, ")") SEPARATOR " , ") AS userInfo'))
    // ->groupBy('deviceId')
    // ->havingRaw('COUNT(DISTINCT id) > 1')
    // ->get()
    // ->toArray();
            
            
$users = DB::table('user_apps')
    ->select('deviceId', DB::raw('GROUP_CONCAT(DISTINCT CONCAT(name, ":", myappid) SEPARATOR ",") AS userInfo'))
    ->groupBy('deviceId')
    ->havingRaw('COUNT(DISTINCT id) > 1')
    ->get()
    ->mapWithKeys(function ($item) {
        $data = explode(',', $item->userInfo);
        $userInfo = [];
        foreach ($data as $val) {
            $parts = explode(':', $val);
            if (count($parts) == 2) {
                $userInfo[$parts[0]] = $parts[1];
            }
        }
        return [$item->deviceId => $userInfo];
    });

            
            
            return response()->json($users)  ;
    }
    
   public function GooglePlay(Request $request){
       
       

      $header = $request->header('Authorization');
     
      if($header!='KRyK3y4MA6ujNjw'){

          return 'ee';
      }

  
  
       $userid=$request->all()["event"]["subscriber_attributes"]["id"]["value"];
 
 
      //  return 'asdasd';

      //  return 'done';
      
      
 $Package=    paymentspackages::where('packageid',$request->all()["event"]["product_id"])->first();
 $user=UserApp::where('id',$userid)->increment('coins', $Package->coins);
   $this->sc($userid,$Package->coins);
 
   $data = [
                 'txn_id'=> $request->all()["event"]["transaction_id"],
                'user_id'=> $userid,
                'package_id'=> $request->all()["event"]["product_id"],
                'method'=> 'Google',
                'cost'=> $request->all()["event"]["price"],

      ];
    
  payments::create([
                'txn_id'=> $request->all()["event"]["transaction_id"],
                'user_id'=> $userid,
                'package_id'=> $request->all()["event"]["product_id"],
                'method'=> 'Google',
                'cost'=> $request->all()["event"]["price"],

            ]);

  return  $Package;

 //   $response = Http::get('http://worldchat.online/api/GetBanner');


   }
    
          public function SetHidden($id){
            $user= UserApp::find($id);
          
    if($user->Hidden==1){
        $user->Hidden=0;
    }else{
        $user->Hidden=1;
    }
    $user->save();
    return $user->Hidden;
            }
     function SetNewIdbyadmin(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
                'newid' => 'required|unique:user_apps,Newid',
             
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
                        $userchaeck=UserApp::where('Newid',$request->newid)->orwhere('myappid',$request->newid)->first();

if($userchaeck!=null){
    
      return $this->returnError(123,'Cant Set NewId');
}
            $user=UserApp::where('id',$request->user_id)->first();
         
             $user->myappid=$request->newid;
             $user->Newid=$request->newid;
             $user->save();
       
            if($user){
               // event(new UserEvent(1,[ 'entry'=>$request->entry],$request->user_id));
                return $this->returnData('SetNewId', $request->NewId);
            }else{
                return $this->returnError(123,'Cant Set NewId');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
        public function addimage(Request $request){
       $rules = [
            
               'image' => 'image|mimes:jpg,string,png,jpeg|max:2048',

              
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
           $image='';
            if($request->hasfile('image')){
                    
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
      return   $fileName ;
               }
 
    }
    
 public function Target() {
     $user= UserApp::where('AgencyKarisma', '>=',1000)-> with('Agency:id,name')->get(['name','AgencyId','AgencyKarisma']);
     return $user;

     }
     
 
    public function WebUserProfile($id){
        $user= UserApp::where('id', $id)->with('Allmyvip.vip','AllmyPayments','AllmyUserGifts','Models')->first();
 
      return $this->returnData('users',$user);
      }
       public function SetUserAdmin($id){
        $user= UserApp::find($id);
      
if($user->Admin==1){
    $user->Admin=0;
}else{
    $user->Admin=1;
}
$user->save();
return $user->Admin;
        }
            
     function SetNewpasswordbyadmin(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
                'password' => 'required',
             
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
                     
            $user=UserApp::where('id',$request->user_id)->first();
         
             $user->password=$request->password;
         
             $user->save();
       
            if($user){
             
                return $this->returnData('SetNewId', $request->NewId);
            }else{
                return $this->returnError(123,'Cant Set NewId');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
        
        
               public function SetUserModiator($id){
        $user= UserApp::find($id);
      
if($user->modiator==1){
    $user->modiator=0;
}else{
    $user->modiator=1;
}
$user->save();
return $user->modiator;
        }
        
        public function SetUserOfficial($id){
            $user= UserApp::find($id);
          
    if($user->Official==1){
        $user->Official=0;
    }else{
        $user->Official=1;
    }
    $user->save();
    return $user->Official;
            }
                 public function SetAnnouncerl($id){
            $user= UserApp::find($id);
          
    if($user->Announcer==1){
        $user->Announcer=0;
    }else{
        $user->Announcer=1;
    }
    $user->save();
    return $user->Announcer;
            }

        public function SetDb($id){
            $user= UserApp::find($id);
          
    if($user->db==1){
        $user->db=0;
         $UserGifts = UserGifts::where([['user_id',$user->id],["image","1680867207.png"]])->delete();
    }else{
        $user->db=1;
           $UserGifts = UserGifts::create([
            "user_id"=>$user->id,
            "svga"=>  "1680867207.svg",
            "title" =>  'DB',
            "message" =>  'DB',
            "image" => "1680867207.png",
            "kind" =>  1,
        ]);
        
               $UserGifts->created_at = $UserGifts->created_at->addDays(100);
        $UserGifts->save() ;
    }
    $user->save();
    return $user->db;
            }


            public function SetUserSupporter($id){
                $user= UserApp::find($id);
              
        if($user->Supporter==1){
            $user->Supporter=0;
        }else{
            $user->Supporter=1;
        }
        $user->save();
        return $user->Supporter;
                }
                public function SetUserSuperAdmin($id){
                    $user= UserApp::find($id);
                  
            if($user->SuperAdmin==1){
                $user->SuperAdmin=0;
            }else{
                $user->SuperAdmin=1;
            }
            $user->save();

            return $user->SuperAdmin;
                    }
                    
                     public function SetCustomeService($id){
                    $user= UserApp::find($id);
                  
            if($user->CustomersService==1){
                $user->CustomersService=0;
                $UserGifts = UserGifts::where([['user_id',$user->id],["image","1678240098.png"]])->delete();
                
                
            }else{
                $user->CustomersService=1;
                  
               
                 $UserGifts = UserGifts::create([
            "user_id"=>$user->id,
            "svga"=>  "1678240098.svg",
            "title" =>  'Service',
            "message" =>  'Service',
            "image" => "1678240098.png",
            "kind" =>  1,
        ]);
               
                 $UserGifts->created_at = $UserGifts->created_at->addDays(100);
        $UserGifts->save() ;
            }
            $user->save();

            return $user->CustomersService;
                    }
public function sc($userid,$coins){
       try {
       event(new UserEvent(6,['coins'=>$coins],$userid));
        return ['coins'=>$coins,'userid'=>$userid];
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
  
}

public function AllUsersWeb(){
    try {
        $user= UserApp::orderBy('coins', 'DESC')->paginate(15);

     return $user;
 } catch (\Exception $ex) {
     return $this->returnError($ex->getCode(), $ex->getMessage());
 }

}

public function Rolletcoin($userid,$coins){
 
      try {
       event(new UserEvent(9,['coins'=>$coins],$userid));
        return ['coins'=>$coins,'userid'=>$userid];
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
  
}
public function Inviteuser(Request $request){
 $user= UserApp::where('id', $request->user_id)->first()->name;
  
      try {
       event(new UserEvent(7,['username'=>$user ,'room_id'=>$request->room_id],$request->user_id));
          return $this->returnData('users', 'done');
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
  
}

   public function GetAllFlags(){

        $users = UserApp::pluck('city')->unique()->all();
        return $this->returnData('users', $users );
      }
   
    public function SearchUser($tittle){

        $users = UserApp::where('myappid','like','%'.$tittle.'%')->orwhere('name','like','%'.$tittle.'%')->orwhere('Newid','like','%'.$tittle.'%')->where('ban',0)->with('myvip.vip')->orderBy('Karisma','DESC')->take(20)->get();
        return $this->returnData('users', $users );
      }

      
    public function getuserRewards($id){
    try {
        $user= UserApp::where('id', $id)->first();
        if($user!=null){
            $user->giftssent = RoomGifts::where([['user_id', $id],['state',0]])->with('gift')->get() ;
            $user->giftscollect = RoomGifts::where([['user_id', $id],['state',1]])->with('gift')->get() ;
            $user->Karisma = Chairs::where('user_id', $id)->first()->Karisma ;

        }
     
        if($user){
            return $this->returnData('users', $user);  
        }else{
            return $this->returnError('536', 'usernotfound');
        }
       return  $user;
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }

    }

 public function getuserRoom($id,$roomid){
    try {
        $myuser=UserApp::where('rememper_token',request()->header('Authorization'))->first();
        
        $user= UserApp::where('id', $id)->with('myvip.vip','Models','family')->first();
        
        if($user!=null){
            $user->giftssent = RoomGifts::where([['user_id', $id],['state',0]])->with('gift')->get() ;
            $user->giftscollect = RoomGifts::where([['user_id', $id],['state',1]])->with('gift')->get() ;
            
                 $frindcheck1=follow::where([['user_id',$id],['sender_id',$myuser->id]])->first();
               $frindcheck2=follow::where([['user_id',$myuser->id],['sender_id',$id]])->first();
         
             if($frindcheck1!=null&&$frindcheck2!=null){
                 $user->FriendState=1;
            }else{
                 $user->FriendState=0;
            }
            $user->ChairKarisma =Chairs::where([['user_id',$id],['room_id',$roomid]])->first()->Karisma ;
        }
        if($user){
            return $this->returnData('users', $user);  
        }else{
            return $this->returnError('536', 'usernotfound');
        }
       return  $user;
    }      catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }

 }

    public function login(Request $request)
    {
        
        try {
            $rules = [
                "phone_number" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
               if($request->phone_number==null){
                   return $this->returnError('E05', 'بيانات الدخول غير صحيحة');
            }
         $user= UserApp::where('phone_number', $request->phone_number)->first();
       $banedlist= baned_devices::where('deviceid',request()->header('DeviceId'))->first();
     if($user){
          $user->deviceId=$request->header('DeviceId');
           $user->UserIP=$request->header('UserIP');
          $user->rememper_token=$user->createToken('token')->plainTextToken;
         $user->save();
        $this->Removeroom($user->id);
      $user->userToken=$user->rememper_token;
           $user->PassApp=$user->password;
             if( $banedlist!=null){
             $user->ban=1;
        }
   // $user->re    DB::table('user_apps')->select('rememper_token')->where('id', $user->id)->get()[0]->rememper_token;
        
        return $this->returnData('users', $user);
    }else{
        return $this->returnData('users', 'E05');  
    }
            if (!$user)
                return $this->returnError('E05', 'بيانات الدخول غير صحيحة');
             
          return $this->returnData('users', $user);  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }
    public function login2(Request $request)
    {
        
        try {
            $rules = [
                "phone_number" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
               if($request->phone_number==null){
                   return $this->returnError('E05', 'بيانات الدخول غير صحيحة');
            }
         $user= UserApp::where('phone_number', $request->phone_number)->first();
       $banedlist= baned_devices::where('deviceid',request()->header('DeviceId'))->first();
     if($user){
          $user->deviceId=$request->header('DeviceId');
           $user->UserIP=$request->header('UserIP');
          $user->rememper_token=$user->createToken('token')->plainTextToken;
         $user->save();
        $this->Removeroom($user->id);
      $user->userToken=$user->rememper_token;
           $user->PassApp=$user->password;
             if( $banedlist!=null){
             $user->ban=1;
        }
   // $user->re    DB::table('user_apps')->select('rememper_token')->where('id', $user->id)->get()[0]->rememper_token;
        
        return $this->returnData('users', ['ban'=>$user->ban,'id'=>$user->id,'Token'=>$user->userToken]);
    }else{
        return $this->returnData('users', 'E05');  
    }
            if (!$user)
                return $this->returnError('E05', 'بيانات الدخول غير صحيحة');
             
          return $this->returnData('users', $user);  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }

    public function loginGoogle(Request $request)
    {
        
        try {
            $rules = [
                "email" => "required|email:strict",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            
            if($request->email==null){
                   return $this->returnError('E05', 'بيانات الدخول غير صحيحة');
            }
         $user= UserApp::where('email',trim($request->email))->first();
                $banedlist= baned_devices::where('deviceid',request()->header('DeviceId'))->first();

        
     if($user){
        $user->deviceId=$request->header('DeviceId');
         $user->UserIP=$request->header('UserIP');
        $user->rememper_token=str_random(100).bcrypt(Carbon::now()).$user->id;
        $user->save();
        if( $banedlist!=null){
             $user->ban=1;
        }
        $this->Removeroom($user->id);
          
             if($user->AgencyId!=null){
        $Agency=Agency::where([['id',$user->AgencyId],['ban',0]])->first();
        $user->agency=$Agency;
        
     }
     $Rooms=Rooms::where([['admin_id',$user->id],['state',0]])->first();
  
           $user->currentroom=$Rooms;
            $user->userToken=$user->rememper_token;
           $user->PassApp=$user->password;

        return $this->returnData('users', $user);  

    }else{
        return $this->returnData('users', 'E05');  
    }
            if (!$user)
                return $this->returnError('E05', 'بيانات الدخول غير صحيحة');
             
          return $this->returnData('users', $user);  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }


   public function loginGoogle2(Request $request)
    {
        
        try {
            $rules = [
                "email" => "required|email:strict",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            
            if($request->email==null){
                   return $this->returnError('E05', 'بيانات الدخول غير صحيحة');
            }
         $user= UserApp::where('email',trim($request->email))->first();
                $banedlist= baned_devices::where('deviceid',request()->header('DeviceId'))->first();

        
     if($user){
        $user->deviceId=$request->header('DeviceId');
         $user->UserIP=$request->header('UserIP');
     $user->rememper_token=$user->createToken('token')->plainTextToken;
        $user->save();
        if( $banedlist!=null){
             $user->ban=1;
        }
        $this->Removeroom($user->id);
          
             if($user->AgencyId!=null){
        $Agency=Agency::where([['id',$user->AgencyId],['ban',0]])->first();
        $user->agency=$Agency;
        
     }
     $Rooms=Rooms::where([['admin_id',$user->id],['state',0]])->first();
  
           $user->currentroom=$Rooms;
            $user->userToken=$user->rememper_token;
           $user->PassApp=$user->password;

  return $this->returnData('users', ['ban'=>$user->ban,'id'=>$user->id,'Token'=>$user->userToken]);
    }else{
        return $this->returnData('users', 'E05');  
    }
          
             
          return $this->returnData('users', ['ban'=>$user->ban,'id'=>$user->id,'Token'=>$user->userToken]); 
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }

     public function loginID(Request $request)
    {
        
        try {
            $rules = [
                "id" => "required",
                "password" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            
             $usersss= UserApp::where('myappid',trim($request->id))->orwhere('Newid',trim($request->id))->first();
          
                             $banedlist= baned_devices::where('deviceid',request()->header('DeviceId'))->first();
  if( $banedlist!=null){
          return $this->returnError('data', 'not found');
        }
             if( $usersss==null){
                 return $this->returnData('users', 'E06');  
             }
          
            
         $user= UserApp::where([['myappid',trim($request->id)],['password',$request->password]])->orwhere([['Newid',trim($request->id)],['password',$request->password]])->first();
       
         if($user==null){
             return $this->returnData('users', 'E05');  
         }
       
        
     if($user){
             $user->rememper_token=str_random(100).bcrypt(Carbon::now()).$user->id;
     
        $user->deviceId=$request->header('DeviceId');
          $user->UserIP=$request->header('UserIP');
        $user->save();
        $this->Removeroom($user->id);
          
             if($user->AgencyId!=null){
        $Agency=Agency::where([['id',$user->AgencyId],['ban',0]])->first();
        $user->agency=$Agency;
     }
     $Rooms=Rooms::where([['admin_id',$user->id],['state',0]])->first();
  
           $user->currentroom=$Rooms;
            $user->userToken=$user->rememper_token;
                $user->PassApp=$user->password;
        return $this->returnData('users', $user);  

    }else{
        return $this->returnData('users', 'E05');  
    }
            if (!$user)
                return $this->returnError('E05', 'بيانات الدخول غير صحيحة');
             
          return $this->returnData('users', $user);  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }


    public function SignUpaccount(Request $request)
    {
        
   
        try {
            $rules = [
            'image' => 'required|image|mimes:jpg,png,jpeg|max:3048',
                "phone_number" => "string|min:10",
                "email" => 'email:strict',
                "name"=> "required|unique:user_apps,name",
                "year"=> "required",
                "day"=> "required",
                "month"=> "required",
                "ginder"=> "required",
                "Flag"=> "required",
                "city"=> "required",
                "notifi_token"=>"required",
            ];
    
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            
            if($request->phone_number==null&&$request->email==null){
                     return $this->returnError('data', 'not found');
            }
            $image=null;
            if($request->hasfile('image')){
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
                $image=$fileName;
               }
               $user = UserApp::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'ginder'=> $request->ginder,
                'year'=> $request->year,
                'month'=> $request->month,
                'day'=> $request->day,
                'image'=> $image,
                  'Hidden'=>0,
                'myappid'=> (string) mt_rand(1000000, 9999999),
                'city'=>$request->city,
                'Flag'=>$request->Flag,
                'email'=>$request->email,
                'notifi_token'=>$request->notifi_token,
                'deviceId'=>$request->header('DeviceId'),
                 'UserIP'=>$request->header('UserIP'),
            ]);
                 $user->rememper_token= str_random(100).bcrypt(Carbon::now()).$user->id;
                     $user->save();
    
            
             $user->userToken=$user->rememper_token;
             
        //           $UserGifts = UserGifts::create([
        //     "user_id"=>   $user->id,
        //     "svga"=>  '1677330469.svg',
        //     "title" =>  'هديه من ورلد',
        //     "message" => '',   
        //     "image" => '1677330469.png',
        //     "kind" =>  0,
        
        // ]);
        //       $UserGifts->created_at=Carbon::now()->addDay(7);
        // $UserGifts->save();
        
        //  $UserGifts2 = UserGifts::create([
        //     "user_id"=>  $user->id, 
        //     "svga"=>  '1677332129.svg',
        //     "title" =>  'هديه من ورلد',
        //     "message" => '',   
        //     "image" => '1677332129.png',
        //     "kind" =>  1,
        
        // ]);
        //  $UserGifts2->created_at=Carbon::now()->addDay(7);
        // $UserGifts2->save();
        
           
            $music=MusicEntry::where('country',$request->city)->first();
             if($music!=null){
                     $user->music=$music->music;
             }
   
     if($user){
        return $this->returnData('users', $user);  

    }else{
        return $this->returnData('users', 'E05');  
    }
            
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }

 



    function UpdateProfile(Request $request){
 if($request->id!=null||$request->frameimage!=null||$request->Level!=null||$request->deviceId!=null||$request->Hidden!=null||$request->Newid!=null||$request->entry!=null||$request->ColoredMessage!=null||$request->AgencyId!=null||$request->AgencyKarisma!=null||$request->notifi_token!=null||$request->coins!=null||$request->rememper_token!=null||$request->myappid!=null||$request->Input!=null||$request->Karisma!=null||$request->phone_number!=null||$request->email!=null){
     
      $insult = insult::create([
            'user_id'=>$request->header('Authorization'),
            'type'=>'update',
            'text'=>'haker',
        ]);
        return 'Fuck';
     
 }
        try {
 
            $rules = [
                'name' => 'string|min:2|max:100',
               'image' => 'image|mimes:jpg,string,png,jpeg|max:2048',

              
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
$user=UserApp::where('rememper_token',$request->header('Authorization'));

 if($user->first()==null){
        return $this->returnError('000',' is wrong');
};

  if($user->first()->phone_number==null&&$user->first()->email==null){
                     return $this->returnError('data', 'not found');
            }
       $states=tap($user)->update($request->only('id','name','description','City','ginder','day','year','month','Flag'))->first();
     
   $image='';
            if($request->hasfile('image')){
                     $icon= $user->first()->image;
                      $path=strstr( $icon,"images");
                       if(File::exists($path)){
                     File::delete($path);
                 }
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
                $image=$fileName;
                $states=tap($user)->update(['image'=>$image])->first()->image;
               }
 

      
       return $this->returnData('users', $user->first());
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
        function UpdateProfiledashboard(Request $request){
 
        try {
 
            $rules = [
                'id' => 'required',
                'name' => 'string|min:2|max:100',
               'image' => 'image|mimes:jpg,string,png,jpeg|max:2048',

              
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
$user=UserApp::where('id',$request->id);

 if($user->first()==null){
        return $this->returnError('000',' is wrong');
};

 
       $states=tap($user)->update($request->only('id','name','phone_number'))->first();
     
   $image='';
            if($request->hasfile('image')){
                     $icon= $user->first()->image;
                      $path=strstr( $icon,"images");
                       if(File::exists($path)){
                     File::delete($path);
                 }
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
                $image=$fileName;
                $states=tap($user)->update(['image'=>$image])->first()->image;
               }
 

      
       return $this->returnData('users', $user->first());
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
    
   function SetPublicIp($id,$ip){
   $user= UserApp::where('id', $id)->first();
   if($user!=null){
         $user->PublicIp=$ip;
     $user->save();
   }
   
    }
    function Setframe(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
                'frame' => 'required',
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
            $userinfo->frameimage=$request->frame;
            $userinfo->save();
            if($userinfo){
                event(new UserEvent(0,[ 'frame'=>$request->frame],$request->user_id));
                return $this->returnData('Setframe', $request->frame);
            }else{
                return $this->returnError(123,'Cant set frame');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
        function SetEnterbubles(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
                'frame' => 'required',
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
            $userinfo->Enterbubles=$request->frame;
            $userinfo->save();
            if($userinfo){
                event(new UserEvent(13,[ 'frame'=>$request->frame],$request->user_id));
                return $this->returnData('Setframe', $request->frame);
            }else{
                return $this->returnError(123,'Cant set frame');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
    
function Setprofilebubles(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
                'frame' => 'required',
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
            $userinfo->bubbles=$request->frame;
            $userinfo->save();
            if($userinfo){
                event(new UserEvent(16,[ 'frame'=>$request->frame],$request->user_id));
                return $this->returnData('Setframe', $request->frame);
            }else{
                return $this->returnError(123,'Cant set frame');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
    function Setbubbles(Request $request){

        try {

            $rules = [
                'user_id' => 'required',
                'bubbles' => 'required',
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
            $userinfo->bubbles=$request->bubbles;
            $userinfo->save();
            if($userinfo){
              //  event(new UserEvent(0,[ 'frame'=>$request->frame],$request->user_id));
                return $this->returnData('Setbubbles', $request->bubbles);
            }else{
                return $this->returnError(123,'Cant set frame');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
       function SetColordmessage(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
                'Color' => 'required',
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
            $userinfo->ColoredMessage=$request->Color;
            $userinfo->save();
            if($userinfo){
              //  event(new UserEvent(0,[ 'frame'=>$request->frame],$request->user_id));
                return $this->returnData('SetColordmessage', $request->Color);
            }else{
                return $this->returnError(123,'Cant set SetColordmessage');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
    // function SetHidden(Request $request){
   
    //     try {

    //         $rules = [
    //             'user_id' => 'required',
    //             'Hidden' => 'required',
            
    //         ];
    //         $validator = Validator::make($request->all(), $rules);
    //         if ($validator->fails()) {
    //             $code = $this->returnCodeAccordingToInput($validator);
    //             return $this->returnValidationError($code, $validator);
    //         }

    //         $userinfo=UserApp::where('id',$request->user_id)->first();
    //         $userinfo->Hidden=$request->Hidden;
    //         $userinfo->save();
    //         if($userinfo){
    //           //  event(new UserEvent(0,[ 'frame'=>$request->frame],$request->user_id));
    //             return $this->returnData('Hiddin', $request->Hidden);
    //         }else{
    //             return $this->returnError(123,'Cant set Hiddin');
    //         }
           
    // } catch (\Exception $ex) {
    //     return $this->returnError($ex->getCode(), $ex->getMessage());
    // }
    // }
    function removebubbles(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
        
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
          
            $userinfo->bubbles=null;
            $userinfo->save();
            if($userinfo){
             //   event(new UserEvent(2,[ 'frame'=>null],$request->user_id));
                return $this->returnData('Setentry', $request->user_id);
            }else{
                return $this->returnError(123,'Cant set frame');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }

    function SetEntry(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
                'entry' => 'required',
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
            $userinfo->entry=$request->entry;
            $userinfo->save();
            if($userinfo){
                event(new UserEvent(1,[ 'entry'=>$request->entry],$request->user_id));
                return $this->returnData('Setentry', $request->entry);
            }else{
                return $this->returnError(123,'Cant set entry');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }

    function SetNewId(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
                'newid' => 'required|unique:user_apps,Newid',
                'myvip_id' => 'required',
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $user=UserApp::where('id',$request->user_id)->first();
            $Vip = MyVip::where('id',$request->myvip_id)->first();
            $Vip ->new_id=$request->newid;
            $user->Newid=$request->newid;
            $user->save();
            $Vip ->save();
            if($user){
               // event(new UserEvent(1,[ 'entry'=>$request->entry],$request->user_id));
                return $this->returnData('SetNewId', $request->NewId);
            }else{
                return $this->returnError(123,'Cant Set NewId');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
    

    function removeframe(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
        
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
          
            $userinfo->frameimage=null;
            $userinfo->save();
            if($userinfo){
                event(new UserEvent(2,[ 'frame'=>null],$request->user_id));
                return $this->returnData('Setentry', $request->user_id);
            }else{
                return $this->returnError(123,'Cant set frame');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
    function removeEntry(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
           
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
            $userinfo->entry=null;
            $userinfo->save();
            if($userinfo){
                event(new UserEvent(3,[ 'entry'=>null],$request->user_id));
                return $this->returnData('Removeentry', $request->user_id);
            }else{
                return $this->returnError(123,'Cant remove entry');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
 function removeEnterbubles(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
           
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
            $userinfo->Enterbubles=null;
            $userinfo->save();
            if($userinfo){
                event(new UserEvent(12,[ 'entry'=>null],$request->user_id));
                return $this->returnData('Removeentry', $request->user_id);
            }else{
                return $this->returnError(123,'Cant remove entry');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
 function RemoveProfilebubles(Request $request){
   
        try {

            $rules = [
                'user_id' => 'required',
           
            
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $userinfo=UserApp::where('id',$request->user_id)->first();
            $userinfo->bubbles=null;
            $userinfo->save();
            if($userinfo){
                event(new UserEvent(15,[ 'entry'=>null],$request->user_id));
                return $this->returnData('Removeentry', $request->user_id);
            }else{
                return $this->returnError(123,'Cant remove entry');
            }
           
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
  function logout(Request $request){
   
        try {

            $userinfo=tap(UserApp::where('id',$request->user_id))->first() ;
            $this->Removeroom($request->user_id);
            return $this->returnData('token', $userinfo);
  
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }

    //---------------------------------->
    public function Verifyaccount(Request $request)
    {
        try {
            $rules = [
                "user_id"=> "required",
                "notifi_token"=> "required",
               
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user= UserApp::where('id', $request->user_id)->with('family.user','family.members','Models.model')->first();
  if(  $user->phone_number==null&&$user->email==null){
                   return $this->returnError(123,'Cant remove entry');
            }
            
           $this->Removeroom($request->user_id);
           $states=tap($user)->update(['notifi_token'=> $request->notifi_token]) ->first();
           $Vip = MyVip::where([['user_id',$user->id],['status',1]])->with('vip')->first();
           $sales=Sales::where([['user_id',$user->id],['status',1]])->with('item')->get();
          
            foreach($sales as $cl) {
                $days =Carbon::now()->diffInDays($cl->created_at,false);
               
    if($days<0 ){
       //return  $cl->id;
        $cl->status=0;
        $cl->save();

        if($cl->item->svggift == $user->frameimage){
  
            $user->frameimage=null;
      
         }
         if($cl->item->svggift == $user->entry){
            $user->entry=null;
           
         }
    }  
   
     }
     $user->rememper_token= str_random(100).bcrypt(Carbon::now()).$user->id;
        $user->save();
    

     $followers=follow::where('user_id',$user->id)->with('user')->get();
       $FOLOLOWING=follow::where('sender_id',$user->id)->with('user')->get();
     $followIds=$FOLOLOWING->pluck('user_id');
    // $followIds2=$FOLOLOWING->pluck('sender_id');
     $Rooms=Rooms::where([['admin_id',$user->id],['state',0]])->first();

   //  $followIds2 = $followIds2->merge($followIds);
 
     if($user->AgencyId!=null){
        $Agency=Agency::where([['id',$user->AgencyId],['ban',0]])->first();
        $user->agency=$Agency;
     }
     $user->giftssent = RoomGifts::where([['user_id', $user->id],['state',0]])->with('gift')->get() ;
     $user->giftscollect = RoomGifts::where([['user_id', $user->id],['state',1]])->with('gift')->get() ;
       
            $user->followers=count( $followers);
            $user->following=count(   $FOLOLOWING);
            $user->friends=count(follow::where([['sender_id',$user->id],['status',1]])->orwhere([['user_id',$user->id],['status',1]])->with('user')->get());
            $user->followIds= $followIds;
            $user->visitors=count(Visitors::where('user_id',$user->id)->get());
            $user->currentroom=$Rooms;
            $user->myvip=$Vip;
             $user->Levelprize=LevelPrize::where('user_id',$user->id)->get()->pluck('endlevel');
             $user->PassApp=$user->password;
             $user->userToken=$user->rememper_token;
             $music=MusicEntry::where('country',$user->city)->first();
             if($music!=null){
                     $user->music=$music->music;
             }
         
           
    if($user){
        return $this->returnData('users', $user);  
    }else{
        return $this->returnData('users', 'E05');  
    }   
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
  public function Verifyaccount2(Request $request)
    {
        try {
            $rules = [
                "Token"=> "required",
                "notifi_token"=> "required",
               
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user= UserApp::where('rememper_token', $request->Token)->with('family.user','family.members','Models.model')->first();
  if(  $user->phone_number==null&&$user->email==null){
                   return $this->returnError(123,'Cant remove entry');
            }
            
           $this->Removeroom($request->user_id);
           $states=tap($user)->update(['notifi_token'=> $request->notifi_token]) ->first();
           $Vip = MyVip::where([['user_id',$user->id],['status',1]])->with('vip')->first();
           $sales=Sales::where([['user_id',$user->id],['status',1]])->with('item')->get();
          
            foreach($sales as $cl) {
                $days =Carbon::now()->diffInDays($cl->created_at,false);
               
    if($days<0 ){
       //return  $cl->id;
        $cl->status=0;
        $cl->save();

        if($cl->item->svggift == $user->frameimage){
  
            $user->frameimage=null;
      
         }
         if($cl->item->svggift == $user->entry){
            $user->entry=null;
           
         }
    }  
   
     }
     $user->rememper_token= str_random(100).bcrypt(Carbon::now()).$user->id;
        $user->save();
    

     $followers=follow::where('user_id',$user->id)->with('user')->get();
       $FOLOLOWING=follow::where('sender_id',$user->id)->with('user')->get();
     $followIds=$FOLOLOWING->pluck('user_id');
    // $followIds2=$FOLOLOWING->pluck('sender_id');
     $Rooms=Rooms::where([['admin_id',$user->id],['state',0]])->first();

   //  $followIds2 = $followIds2->merge($followIds);
 
     if($user->AgencyId!=null){
        $Agency=Agency::where([['id',$user->AgencyId],['ban',0]])->first();
        $user->agency=$Agency;
     }
     $user->giftssent = RoomGifts::where([['user_id', $user->id],['state',0]])->with('gift')->get() ;
     $user->giftscollect = RoomGifts::where([['user_id', $user->id],['state',1]])->with('gift')->get() ;
       
            $user->followers=count( $followers);
            $user->following=count(   $FOLOLOWING);
            $user->friends=count(follow::where([['sender_id',$user->id],['status',1]])->orwhere([['user_id',$user->id],['status',1]])->with('user')->get());
            $user->followIds= $followIds;
            $user->visitors=count(Visitors::where('user_id',$user->id)->get());
            $user->currentroom=$Rooms;
            $user->myvip=$Vip;
             $user->Levelprize=LevelPrize::where('user_id',$user->id)->get()->pluck('endlevel');
             $user->PassApp=$user->password;
             $user->userToken=$user->rememper_token;
             $music=MusicEntry::where('country',$user->city)->first();
             if($music!=null){
                     $user->music=$music->music;
             }
         
           
    if($user){
        return $this->returnData('users', $user);  
    }else{
        return $this->returnData('users', 'E05');  
    }   
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //------------------------------------------------------------------------->

      function userinfo(Request $request){ 
        try {
            
          
            $user= UserApp::where('rememper_token',$request->header('Authorization'))->with('family.user','family.members','Models.model')->first();
            
            if(  $user->ban==1){
                      return $this->returnError(123,'Cant remove entry');
            }
          
              $banedlist= baned_devices::where('deviceid',$user->deviceId)->first();
              if($banedlist!=null){
                              return $this->returnError(123,'Cant remove entry');
              }
            
              $user->rememper_token= $user->createToken('token')->plainTextToken;
                $user->UserIP=$request->header('UserIP');
                  $user->deviceId=$request->header('DeviceId');
              $user->save();
            $this->Removeroom($user->id);
            $StartBanner=StartBanner::where('status',1)->first();
            $sales=Sales::where([['user_id',$user->id],['status',1]])->with('item')->get();
            $Vip = MyVip::where([['user_id',$user->id],['status',1]])->with('vip')->first();
             $Prizes=UserGifts::where('user_id',$user->id)->get();
     

             
        foreach($Prizes as $cl) {
                $days =Carbon::now()->diffInDays($cl->created_at,false);
               
            if($days<0 ){
          $cl->delete();
          
          if($cl->svga == $user->frameimage){
            $user->frameimage=null;
           }
         if($cl->svga == $user->entry){
            $user->entry=null;
         }
    }  
     }
        
         if($Vip!=null){
            $Vipdays =Carbon::now()->diffInDays( $Vip->created_at,false);
            if($Vipdays<0 ){
               
                $Vip->endstatus=0;
              
                if($Vip->vip->Entry==$user->entry){
                 
                    $user->entry=null;
                 }
                 if($Vip->vip->Frame==$user->frameimage){
                    $user->frameimage=null;
                 }
                 if($Vip->vip->ProfileEntry==$user->bubbles){
                    $user->bubbles=null;
                 }
                 $user->Newid=null;
                 $user->Hidden=0;
                 $user->ColoredMessage=null;
                 $user->save();
                 $Vip->save();
                 $Vip=null;
          
             }  
         
         }
            foreach($sales as $cl) {
                $days =Carbon::now()->diffInDays($cl->created_at,false);
               
            if($days<0 ){
          $cl->status=0;
          $cl->save();
          if($cl->item->svggift == $user->frameimage){
            $user->frameimage=null;
           }
         if($cl->item->svggift == $user->entry){
            $user->entry=null;
         }
    }  
     }

       
 
     
     $followers=follow::where('user_id',$user->id)->get()->pluck('sender_id');
      $followeing=follow::where('sender_id',$user->id)->get()->pluck('user_id');
      $Freinds=UserApp::whereIn('id',$followers)->whereIn('id',$followeing)->get();
     $JoinAgency=JoinAgencyRequest::where([['user_id',$user->id],['status',0]])->get();
      
        $followIds=$followeing;
      $JoinAgencyids=$JoinAgency->pluck('agancy_id');
     $followIds2 = $followIds;
     if($user->AgencyId!=null){
        $Agency=Agency::where([['id',$user->AgencyId],['ban',0]])->first();
        $user->agency=$Agency;
     }
     $Rooms=Rooms::where([['admin_id',$user->id],['state',0]])->first();
  
           $user->currentroom=$Rooms;
            $user->followers=count( $followers);
            $user->following=count( $followeing);
            $user->friends=count($Freinds);
            $user->followIds= $followIds2;
            $user->visitors=count(Visitors::where('user_id',$user->id)->get());
            $user->myvip=$Vip;
           
            $user->JoinAgencyids=$JoinAgencyids;
            $user->StartBanner=$StartBanner;
            $user->familyrequest=Families_Members::where([['user_id',$user->id],['status',0]])->pluck('Family_id');
                $user->Levelprize=LevelPrize::where('user_id',$user->id)->get()->pluck('endlevel');
              $user->userToken=$user->rememper_token;
           $user->PassApp=$user->password;
           $user->MessageNumber=count(InboxRoom::where([['user_id',$user->id],['status',1],['number_unread','!=',0]])->orwhere([['sender_id',$user->id],['status',1],['number_unread','!=',0]])->with('message','user','sender')->get());
            
            if($user) {
                return $this->returnData('users',$user);
            } else {
                return $this->returnSuccessMessage();
            }
            
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }

      }

   function userinfo22(Request $request){ 
        try {
            return 'asd';
          
            $user= UserApp::where('rememper_token',$request->header('Authorization'))->with('family.user','family.members','Models.model')->first();
            
            // if(  $user->ban==1){
            //           return $this->returnError(123,'Cant remove entry');
            // }
            
            if(  $user->phone_number==null&&$user->email==null){
                   return $this->returnError(123,'Cant remove entry');
            }
            
            
              $user->rememper_token= $user->createToken('token')->plainTextToken;
                $user->UserIP=$request->header('UserIP');
                  $user->deviceId=$request->header('DeviceId');
              $user->save();
            $this->Removeroom($user->id);
            $StartBanner=StartBanner::where('status',1)->first();
            $sales=Sales::where([['user_id',$user->id],['status',1]])->with('item')->get();
            $Vip = MyVip::where([['user_id',$user->id],['status',1]])->with('vip')->first();
             $Prizes=UserGifts::where('user_id',$user->id)->get();
             
             
        foreach($Prizes as $cl) {
                $days =Carbon::now()->diffInDays($cl->created_at,false);
               
            if($days<0 ){
          $cl->delete();
          
          if($cl->svga == $user->frameimage){
            $user->frameimage=null;
           }
         if($cl->svga == $user->entry){
            $user->entry=null;
         }
    }  
     }
        
         if($Vip!=null){
            $Vipdays =Carbon::now()->diffInDays( $Vip->created_at,false);
            if($Vipdays<0 ){
               
                $Vip->endstatus=0;
              
                if($Vip->vip->Entry==$user->entry){
                 
                    $user->entry=null;
                 }
                 if($Vip->vip->Frame==$user->frameimage){
                    $user->frameimage=null;
                 }
                 if($Vip->vip->ProfileEntry==$user->bubbles){
                    $user->bubbles=null;
                 }
                 $user->Newid=null;
                 $user->Hidden=0;
                 $user->ColoredMessage=null;
                 $user->save();
                 $Vip->save();
                 $Vip=null;
          
             }  
         
         }
            foreach($sales as $cl) {
                $days =Carbon::now()->diffInDays($cl->created_at,false);
               
            if($days<0 ){
          $cl->status=0;
          $cl->save();
          if($cl->item->svggift == $user->frameimage){
            $user->frameimage=null;
           }
         if($cl->item->svggift == $user->entry){
            $user->entry=null;
         }
    }  
     }

       
 
     
     $followers=follow::where('user_id',$user->id)->get()->pluck('sender_id');
      $followeing=follow::where('sender_id',$user->id)->get()->pluck('user_id');
      $Freinds=UserApp::whereIn('id',$followers)->whereIn('id',$followeing)->get();
     $JoinAgency=JoinAgencyRequest::where([['user_id',$user->id],['status',0]])->get();
      
        $followIds=$followeing;
      $JoinAgencyids=$JoinAgency->pluck('agancy_id');
     $followIds2 = $followIds;
     if($user->AgencyId!=null){
        $Agency=Agency::where([['id',$user->AgencyId],['ban',0]])->first();
        $user->agency=$Agency;
     }
     $Rooms=Rooms::where([['admin_id',$user->id],['state',0]])->first();
  
           $user->currentroom=$Rooms;
            $user->followers=count( $followers);
            $user->following=count( $followeing);
            $user->friends=count($Freinds);
            $user->followIds= $followIds2;
            $user->visitors=count(Visitors::where('user_id',$user->id)->get());
            $user->myvip=$Vip;
           
            $user->JoinAgencyids=$JoinAgencyids;
            $user->StartBanner=$StartBanner;
            $user->familyrequest=Families_Members::where([['user_id',$user->id],['status',0]])->pluck('Family_id');
                $user->Levelprize=LevelPrize::where('user_id',$user->id)->get()->pluck('endlevel');
              $user->userToken=$user->rememper_token;
           $user->PassApp=$user->password;
           $user->MessageNumber=count(InboxRoom::where([['user_id',$user->id],['status',1],['number_unread','!=',0]])->orwhere([['sender_id',$user->id],['status',1],['number_unread','!=',0]])->with('message','user','sender')->get());
            
            if($user) {
                return $this->returnData('users',$user);
            } else {
                return $this->returnSuccessMessage();
            }
            
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }

      }
           function UserProfileData($id,$userid){
            //   if($id==$userid){
            //       return 'ddd';
            //   }
          try {
             $user= UserApp::where('id',$id)->with('Myvip.vip','myjoindroom','myroom','Models.model','ProfileImage','family')->first();
              $userssss= UserApp::where('id',$userid)->first();
            $Visitors = Visitors::where([['user_id',$id],['visitor_id',$userid]])->first();
          
            if($Visitors!=null){
 
            }else{
                
                
                
                event(new UserEvent(18,['message'=> "  بزياره ملفك الشخصي  ".   $userssss->name   . '  قام '],$id));
                $Visitors = Visitors::create([ 
                    'user_id'=> $id,
                    'visitor_id'=> $userid,
                ]);
            }
      
            $user->giftssent = RoomGifts::where([['user_id', $user->id],['state',0]])->with('gift')->get() ;
            $user->giftscollect = RoomGifts::where([['user_id', $user->id],['state',1]])->with('gift')->get() ;
            $user->followers=count(follow::where('user_id',$user->id)->with('user','otheruser')->get());
            $user->following=count(follow::where('sender_id',$user->id)->with('user','otheruser')->get());
            $user->friends=count(follow::where([['sender_id',$user->id],['status',1]])->orwhere([['user_id',$user->id],['status',1]])->with('user')->get());
            $user->Postes=Postes::where('user_id',$user->id)->with('user','like.user','commentsuser.user')->get();
            $user->visitors=count(Visitors::where('user_id',$user->id)->get());
            $frindcheck1=follow::where([['user_id',$id],['sender_id',$userid]])->first();
            $frindcheck2=follow::where([['user_id',$userid],['sender_id',$id]])->first();
          $user->Relations   = Relations::where([['sender_id',$id],['status',1]])->orwhere([['user_id',$id],['status',1]])->with('item:id,name,svggift','user','anotheruser')->get();
             if($frindcheck1!=null&&$frindcheck2!=null){
                 $user->FriendState=1;
            }else{
                 $user->FriendState=0;
            }
            $user->Blockeduser=BlockList::where([['user_id',$id],['sender_id',$userid]])->orwhere([['user_id',$userid],['sender_id',$id]])->first();
           
            if($user){
                return $this->returnData('users',$user);
            }else{
                return $this->returnSuccessMessage();
   
            }
            
   
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }

      }

      function mygifts(Request $request){
        try {
       
        
           $giftssent = RoomGifts::where([['user_id', $request->user_id],['state',0]])->with('gift')->get() ;
           $giftscollect = RoomGifts::where([['user_id',$request->user_id],['state',1]])->with('gift')->get() ;
       
            if(  $giftssent ){
                  
                return $this->returnData('users', ['giftssent'=>$giftssent,'giftscollect'=>$giftscollect]);
            }else{
                return $this->returnSuccessMessage();
   
            }
            
   
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }

      }

      function joinuserbyid($id){
        try {
            $user= UserApp::where('id',$id)->first();
            if( $user){
                return $this->returnData('users', $user);
            }else{
                return $this->returnSuccessMessage();
            }
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
      }

      function  userbyToken(Request $request){
        try {
            $user= UserApp::where('rememper_token',$request->rememper_token)->first();

            if( $user){
                return $this->returnData('users', $user);
            }else{
                return $this->returnSuccessMessage();
            }
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
      }

      public function Removeroom($id){

    $Joinroom=Joinroom::where([['user_id',$id],['state',0]])->get();
  
    foreach($Joinroom as $row) {
      $row->state = 1;  
      $row->index = 1;
      event(new RoomEvent(16, $row->user_id,$row->room_id));
      $row->save();
    }

$Chairs=Chairs::where([['user_id',$id],['chair_id','!=',9]])->get();
foreach($Chairs as $row) {
    $row->	user_id = null ;  
    $row->	Karisma = 0 ;  
    $row->save();
  }

      }
   
   
      

 
 

      function  GetRoomUserFollowing($id){ 
    
       try {
        $Follow= follow::where('sender_id',$id)->get()->pluck('user_id');
        $Rooms= Rooms::whereIn('admin_id', $Follow)->where('state', 0)->latest()->get();
        return $this->returnData('Rooms',$Rooms);

} catch (\Exception $ex) {
   return $this->returnError($ex->getCode(), $ex->getMessage());
} 
      }
      function  GetPostsUserFollowing($id){ 
    
        try {
         $Follow= follow::where('sender_id',$id)->get()->pluck('user_id');
         $Postes= Postes::whereIn('user_id', $Follow)->where('status',1)->latest()->with('user','like.user','commentsuser.user')->paginate(6);
         return $this->returnData('Postes',$Postes);
 
 } catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
 } 
       }
      
 
      

}
