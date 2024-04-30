<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Validator;
use Carbon\Carbon;
use App\Models\Rechargesbalance;
class RechargesbalanceController extends Controller
{   
     use GeneralTrait;
 
     public function GetRecharges(){
   $eer=Rechargesbalance::orderBy('created_at', 'DESC')->with('user:id,name,image,myappid')->paginate(10);
    return $this->returnData('Rechargesbalance',$eer);
         
        $Rechargesbalance=Rechargesbalance::orderBy('created_at', 'DESC')->with('user:id,name,image,myappid')->paginate(10);
      
        return $this->returnData('Rechargesbalance',$Rechargesbalance);
     }
     
     
     public function DelayCharge(){
        $Daybalance=Rechargesbalance::whereDate('created_at', Carbon::today())->get()->sum('cost') ;
        $Monthbalance=Rechargesbalance::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get()->sum('cost') ;

        return $this->returnData('Rechargesbalance',[
            "Daybalance"=>round($Daybalance),
            "Monthbalance"=>round($Monthbalance)
    ]);
     }
     
}
//