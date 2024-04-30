<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Cacher;
use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use App\Models\background;
use App\Models\baned_devices;
use App\Models\Banner;
use App\Models\emoji;
use App\Models\emojicategory;
use App\Models\GiftCategory;
use App\Models\roomcategory;
use App\Models\ShippingPackage;
use Illuminate\Http\Request;

class ConstDataController extends Controller
{
    private  $cacher;
    public function __construct(Cacher $cache)
    {
        $this->cacher = $cache;
    }




    public function indexAction()
    {
        $Data = $this->cacher->getCached("ConstData");
        if (is_null($Data)) {
            $setData = $this->ConstData();
            $this->cacher->setCached("ConstData", json_encode($setData));
            return $setData;
        } else {
            return  $this->cacher->getCached("ConstData");
        }
    }

    public function deleteData()
    {

        $this->cacher->removeCached("ConstData");

        return response()->json(["message" => "Delete Const Data"]);
    }






    protected  function ConstData()
    {
        $banstatus = 0;
        $Banner = Banner::where('status', 1)->orderBy('sorting', 'DESC')->get();
        $catigoris = GiftCategory::where('status', 1)->orderBy('sorting', 'DESC')->with('gifts:category_id,name,id,image,price,luckypackage')->get(['id', 'name', 'lucky', 'vip']);
        $luckycatigoris = GiftCategory::where([['lucky', 1], ['status', 1]])->with('gifts:category_id,name,id,image,price')->get(['id', 'name', 'lucky']);
        $roomcategory = roomcategory::get(['id', 'name']);
        $background = background::where('status', 1)->get();
        $Shipping =    ShippingPackage::where('status', 1)->get();
        $version =    AppVersion::all()->first();
        $emoji =    emoji::where('status', 1)->get();
        $emojicategory =  emojicategory::with("emoji")->orderBy('sorting', 'DESC')->get();
        $banedlist = baned_devices::where('deviceid', request()->header('DeviceId'))->first();
        if ($banedlist != null) {
            $banstatus = 1;
        }
        return  ['banstatus' => $banstatus, 'emoji' => $emoji, 'emojiCategory' => $emojicategory, 'luckycatigoris' => $luckycatigoris, 'version' => $version, 'Banner' => $Banner, 'catigoris' => $catigoris, 'Roomcategory' => $roomcategory, 'background' => $background, 'Shipping' => $Shipping];
    }
}
