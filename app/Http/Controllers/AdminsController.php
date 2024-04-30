<?php

namespace App\Http\Controllers;
use App\Models\Admins;
use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
class AdminsController extends Controller
{
    use GeneralTrait;
    public function GetAdmins(){

        try {
       $Admins=Admins::all();
       return $this->returnData('Admins', $Admins );
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }
     public function LoginAdmin($name,$password){

        try {
       $Admins=Admins::where([['name',$name],['password',$password]])->first();
   
       if($Admins!=null){
      $Admins->last_login=Carbon::now();
        $Admins->save();
        return $this->returnData('Admins', $Admins );
      }else{
        return $this->returnError('E001', 'Not Found');

      }
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }

     public function CheckAdminlogin($id ){

        try {
       $Admins=Admins::where('id',$id)->first();
   
       if($Admins!=null){
      $Admins->last_login=Carbon::now();
        $Admins->save();
        return $this->returnData('Admins', $Admins );
      }else{
        return $this->returnError('E001', 'Not Found');

      }
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
     }



     public function AddAdmins(Request $request){
        try {
        $rules = [
            "name" => "required|unique:agencies,name",
            "role"=> "required",
            'password'=> "required",

        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
       
            $Admin = Admins::create([
            'name'=> $request->name,
            "role"=> $request->role,
            'password'=> $request->password,
         
        ]);
        
        if($Admin){
            return $this->returnData('Admin',$Admin );
            }else{
                return $this->returnError('E001', 'Can\'t add Admin');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    

    function ChangeAdminState($id){
        $Admins=Admins::where('id',$id)->first();
        if($Admins->ban==1){
            $Admins->ban=0;
        }else{
            $Admins->ban=1;
        }
        $Admins->save();
         return $this->returnData('Banner',$Admins);        
        }

}
