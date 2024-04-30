<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;

use App\Models\PaypalPackage;
class PaypalPackageController extends Controller
{
    
     use GeneralTrait;
  public function GetPaypalPackage(){

    try {

   $PaypalPackage=PaypalPackage::where('status',1)->get();
   return $this->returnData('PaypalPackage', $PaypalPackage );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
}
