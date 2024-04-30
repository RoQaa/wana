<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\GiftCategory;
use App\Models\roomcategory;
use App\Traits\GeneralTrait;
use Validator;
use App\Models\emojicategory;

use Carbon\Carbon;
use App\Models\UserApp;
use App\Models\Rooms;
use App\Models\Agency;
use App\Models\AgencyRecordes;
use App\Models\LevelFrames;
use App\Http\Controllers\RtcTokenBuilder2;
use App\Models\background;
use App\Models\ShippingPackage;
use App\Models\AppVersion;
use App\Models\Chairs;
use App\Models\baned_devices;
use App\Models\emoji;
use App\Models\StartBanner;
use App\Models\VipCenter;
use App\Models\AdminGifts;
class BannerController extends Controller
{
    use GeneralTrait;

    public function  GetPersantage($cost){
 $PRSENTAGEP=0;
    $Randomnumber= mt_rand(1, 1000);
    if($Randomnumber<400){
        $PRSENTAGEP= 0;
    }else if($Randomnumber>=400&&$Randomnumber<500){
        $PRSENTAGEP= 10;

    }else if($Randomnumber>=500&&$Randomnumber<600){
        $PRSENTAGEP= 30;

    }else if($Randomnumber>=600&&$Randomnumber<700){
        $PRSENTAGEP= 40;

    }else if($Randomnumber>=700&&$Randomnumber<800){

        $PRSENTAGEP= 50;
    }else if($Randomnumber>=800&&$Randomnumber<900){

        $PRSENTAGEP= 60;
    }else if($Randomnumber>=950&&$Randomnumber<1000){

        $PRSENTAGEP= 70;
    }else if($Randomnumber==1000){
        $PRSENTAGEP= 90;
    }else{
        $PRSENTAGEP= 0;
    }

    return ['win'=>($PRSENTAGEP*$cost)/100, 'Persantage'=>$PRSENTAGEP, 'Randomnumber'=>$Randomnumber ];
        //echo ($items[array_rand($items)]*$cost)/100   ;
    }


    public function AddBanner(Request $request){
        try {
        $rules = [
          //  "Room_id" => "required",
            "image" => "required",
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

           $Banner = Banner::create([
            'image'=> $image,

           // 'Room_id'=>$request->Room_id,

        ]);

        $Banner ->status=1;
        if($Banner){
            return $this->returnData('Banner',$Banner);
            }else{
                return $this->returnError('E001', 'Can\'t add Banner');
         }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


    function GetConstData(){
        $banstatus=0;
        $Banner=Banner::where('status',1)->orderBy('sorting', 'DESC')->get();
        $catigoris=GiftCategory::where('status',1)->orderBy('sorting', 'DESC')->with('gifts:category_id,name,id,image,price,luckypackage')->get(['id','name','lucky','vip']);
        $luckycatigoris=GiftCategory::where([['lucky',1],['status',1]])->with('gifts:category_id,name,id,image,price')->get(['id','name','lucky']);
        $roomcategory=roomcategory::get(['id','name']);
        $background=background::where('status',1)->get();
        $Shipping=    ShippingPackage::where('status',1)->get();
        $version=    AppVersion::all()->first();
        $emoji=    emoji::where('status',1)->get();
        $emojicategory=  emojicategory::with("emoji")->orderBy('sorting', 'DESC')->get();
         $banedlist= baned_devices::where('deviceid',request()->header('DeviceId'))->first();
        if($banedlist!=null){
            $banstatus=1;
        }

        return  [ 'banstatus'=>$banstatus,'emoji'=>$emoji,'emojiCategory'=>$emojicategory,'luckycatigoris'=>$luckycatigoris,'version'=>$version,'Banner'=>$Banner,'catigoris'=>$catigoris,'Roomcategory'=>$roomcategory,'background'=>$background,'Shipping'=>$Shipping];

 }

     function GetConstData2(Request $request){
        $banstatus=0;
        $Banner=Banner::where('status',1)->orderBy('sorting', 'DESC')->get();
        $catigoris=GiftCategory::where('status',1)->orderBy('sorting', 'DESC')->with('gifts:category_id,name,id,image,price,luckypackage')->get(['id','name','lucky','vip']);
        $luckycatigoris=GiftCategory::where([['lucky',1],['status',1]])->with('gifts:category_id,name,id,image,price')->get(['id','name','lucky']);
        $roomcategory=roomcategory::get(['id','name']);
        $background=background::where('status',1)->get(['id','image']);
        $Shipping=    ShippingPackage::where('status',1)->get();
        $version=    AppVersion::all()->first();
        $emoji=    emoji::where('status',1)->get();
        $emojicategory=  emojicategory::with("emoji")->orderBy('sorting', 'DESC')->get();
         $banedlist= baned_devices::where('deviceid',request()->header('DeviceId'))->first();
        // $banedlist= baned_devices::where('deviceid',request()->header('DeviceId'))->orwhere([['DeviceIp',request()->header('UserIP')],['DeviceIp','!=',null]])->first();
        if($banedlist!=null){
            $banstatus=1;
        }

        return  [ 'banstatus'=>$banstatus,'emoji'=>$emoji,'emojiCategory'=>$emojicategory,'luckycatigoris'=>$luckycatigoris,'version'=>$version,'Banner'=>$Banner,'catigoris'=>$catigoris,'Roomcategory'=>$roomcategory,'background'=>$background,'Shipping'=>$Shipping];

 }


 function GetConstwebData(){

    $Vips=VipCenter::where('status',1)->get();
    $AdminGifts=AdminGifts::all();
    $Users=UserApp::count();
    $Agency=Agency::count();
    $Rooms=Rooms::where('state',0)->count();
    $Roomstoday=Rooms::whereDate('created_at', Carbon::today())->count();
    $Chairscount=Chairs::where([['chair_id','!=',9],['user_id','!=',null]])->count();
    $AgencyRecordes=AgencyRecordes::whereDate('created_at', Carbon::today())->sum('karisma');
    $Userstoday=UserApp::whereDate('created_at', Carbon::today())->count();
    return  [ 'Vip'=>$Vips,'Rooms'=>$Rooms,'Chairscount'=>$Chairscount,'Roomstoday'=>$Roomstoday,'Agencytoday'=>$AgencyRecordes,'AgencyNumber'=> $Agency,'AdminGifts'=>$AdminGifts,'Usernumber'=>$Users,'Userstoday'=>  $Userstoday];

}
function GetBanner(){
      $Banners=Banner::all() ;
      $StartBanner=StartBanner::all()->first();
      $background=background::all();
      return  ['baners'=>$Banners,'background'=>$background,'StarterBanner'=>$StartBanner,];
}
function deleteBanner($id){
$Banners=Banner::where('id',$id)->delete();
 return $this->returnData('Banner',$Banners);
}
function ChangeBannerState($id){
    $Banners=Banner::where('id',$id)->first();
    if($Banners->status==1){
        $Banners->status=0;
    }else{
        $Banners->status=1;
    }
    $Banners->save();
     return $this->returnData('Banner',$Banners);
    }
    function deleteBackground($id){
        $Banners=background::where('id',$id)->delete();
         return $this->returnData('Banner',$Banners);
        }
    function ChangeBackgroundrState($id){
        $Banners=background::where('id',$id)->first();
        if($Banners->status==1){
            $Banners->status=0;
        }else{
            $Banners->status=1;
        }
        $Banners->save();
         return $this->returnData('Banner',$Banners);
        }
}
