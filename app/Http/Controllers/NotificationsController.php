<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
 use App\Models\UserApp;
use Validator;
use App\Traits\GeneralTrait;
use App\Models\Notifications;
class NotificationsController extends Controller
{
    use GeneralTrait;
    public function AddUserNotification(Request $request){
        try {

            $rules = [
                "tittle"=> "required",
                "message" => "required|string",
                
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
                $image=$fileName;
               } 
            

           $Notification = Notifications::create([
            "tittle" =>  $request->tittle,
            "message" =>  $request->message,
            "image" => $image,

        ]);
       
        if($Notification){
            return $this->returnData('Notification',$Notification);
            }else{
                return $this->returnError('E001', 'Can\'t add Notification');
         }  
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    //-------------------------------------------

    public function GetUserNotification(){
    
        $UserNotifications=Notifications:: orderBy('id', 'desc')->get() ;
             
             
                return $this->returnData('UserNotifications',$UserNotifications);
        
                }
                
 //-------------------------------------------
 
     function notificationallusers($title,$body) {
    $SERVER_API_KEY ='AAAAyhKgldg:APA91bHBA7jxwACZ86jHClELPFPzt8Rtk09p6lBVyfKdperVNQYqmYP6Fp2ky3BZ5TXATWBRt5Bj1IdfTtCdgJWD47oUQBRaekh5w1P72rg45jxySnWJMvu-MtMc43scsf0J6ek_vJ50';

   // $token_1 = 'cEUSBICKSUeq9o9iKxZu4M:APA91bEutjHmKRtJbv3f41-nQX_5Ra9UDCNOR79SX_CJZus1HuPhzdxKt-ZCG3SUX-ZxgO-XHGXETTHckWG1A68CnwG088ZczrdWeWUT53gcE9za4CnDWg4LcIbKpTSL9hz49SHnC7zS';
 
   $uio=   UserApp::whereNotNull('notifi_token')->pluck('notifi_token')->take(1000)
                ->all();

     
   
    $data = [
        "registration_ids" =>$uio,
        "notification" => [
            "title" => $title,
            "body" => $body,
            "sound"=> "default" // required for sound on ios
        ],
        "data" => [
            "asd"=>'asdasd'
            ]
    ];
    $dataString = json_encode($data);
    $headers = [
        'Authorization: key='.$SERVER_API_KEY,
        'Content-Type: application/json',
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
   $response = curl_exec($ch);
    dd($response);
} 

 public function sendWebNotification()
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = UserApp::whereNotNull('notifi_token')->pluck('notifi_token')->all();
          
        $serverKey = 'AAAAHRl398M:APA91bGAVNNxASOk779z0-URT9q-wYOiam0d-aJFVdaOQipcnz8Q9xedAWFikF9CRGmFQVaHFwakhQe3N0GtKeOZKqyO74eIEz79KPHjlk52MOeiuvRLfcjj85ctnLAaZMU9a4E2quPI';
  
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => 'gigo',
                "body" => 'gigo',  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);        
    }

 
 
}
