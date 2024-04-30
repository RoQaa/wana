<?php

namespace App\Http\Controllers;

use Validator;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Agency;
use App\Models\UserApp;
use App\Models\UserGifts;
use File;
class AgencyController extends Controller
{
    use GeneralTrait;
    //--------------------------------------AddCategory
    
    
      function UpdateAgecy(Request $request){
 
        try {
 
            $rules = [
                   "id"=> "required",
              

              
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
$Agency=Agency::where('id',$request->id);

 if($Agency->first()==null){
        return $this->returnError('000',' is wrong');
};

 
       $states=tap($Agency)->update($request->only('id','name','appid','AgencyKind','phonenumber'))->first();
     
   $image='';
            if($request->hasfile('image')){
                     $icon= $Agency->first()->image;
                      $path=strstr( $icon,"images");
                       if(File::exists($path)){
                     File::delete($path);
                 }
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
                $image=$fileName;
                $states=tap($Agency)->update(['image'=>$image])->first()->image;
               }
 

      
       return $this->returnData('users', $Agency->first());
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }
    
        public function AddAgency(Request $request){
            try {
            $rules = [
                "name" => "required|unique:agencies,name",
                "user_id"=> "required",
                'AgencyKind'=> "required",
                'image'=> "required",
                'password'=> "required",

            ];
            $validator = Validator::make($request->all(), $rules);
        
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $image='';
            $svgimage='';
            if($request->hasfile('image')){
                $fileName =time().'.png';   
                $file1 = $request->image->move(public_path('images'),$fileName);
                $image=$fileName;
               } 
          
               $user=UserApp::where('myappid',$request->user_id)->orwhere('Newid',$request->user_id)->first();
           //    return $user;
               if($request->AgencyKind==0||$request->AgencyKind==2){
                  $user->update(['MemberAgency'=>1]); 
               }
                if($request->AgencyKind==1||$request->AgencyKind==2){
                  $user->update(['MoneyAgency'=>1]); 
               }
           
               
        //          $UserGifts = UserGifts::create([
        //     "user_id"=>$user->id,
        //     "svga"=>  "1678237219.svg",
        //     "title" =>  '',
        //     "message" =>  '',
        //     "image" => "1678237219.png",
        //     "kind" =>  1,
        // ]);
               
        //          $UserGifts->created_at = $UserGifts->created_at->addDays(100);
        // $UserGifts->save() ;
               
               
               
               $Agency = Agency::create([
                'name'=> $request->name,
                'user_id'=>  $user->id,
                'AgencyKind'=> $request->AgencyKind,
                'image'=> $image,
                'password'=> $request->password,
                'appid'  => (string) mt_rand(1000000, 9999999),
                'phonenumber'=> $request->phonenumber,
                 'AdminGetter'=> $request->AdminGetter,
            ]);
            $user->AgencyId=$Agency->id;
            $user->AgencyKarisma=0;
            $user->save();
            $Agency=Agency::where('id',$Agency->id)->with('user.myvip','Members','Agencypayments.user')->first();

            if($Agency){
                return $this->returnData('Agency',$Agency);
                }else{
                    return $this->returnError('E001', 'Can\'t add Agency');
             }  
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }
  //--------------------------------------

  public function AgencyLogin($name,$password){

    try {

   $Agency=Agency::where([['appid',$name],['password',$password],['ban',0]])->with('user.myvip' ,'Agencypayments.user')->first();
 
  
   if($Agency!=null){
 
      return $this->returnData('Agency', $Agency );
    }else{
      return $this->returnError('E001', 'Not Found');

    }
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}}

  public function EditAgencyName($id,$name){

    try {

   $Agency=Agency::where('id',$id)->first();
  UserApp::where('id',$Agency->user_id)->decrement('coins', 5000);
   $Agency->update(['name'=>$name]);
   if($Agency){
 
      return $this->returnData('Agency', $Agency );
    }else{
      return $this->returnError('E001', 'Not Found');

    }
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}}


  public function Agencymembers($id){

    try {
        $UserApp=UserApp::where("AgencyId",$id)->orderBy('AgencyKarisma','DESC')->paginate(10);
        return $UserApp ;

   if($UserApp!=null){
 
      return $this->returnData('Users', $UserAp );
    }else{
      return $this->returnError('E001', 'Not Found');

    }
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}}

  public function CloseAgency($id){
        $Agency=Agency::where('id',$id)->first();
          $Agency->update(['ban'=>1]);
            UserApp::where('id',$Agency->user_id)->first()->update(['MemberAgency'=>0,'MoneyAgency'=>0,'AgencyId'=>null,'AgencyKarisma'=>0,'frameimage'=>null]);
              UserApp::where('AgencyId',$id)->update(['AgencyId'=>null,'AgencyKarisma'=>0]);
              $usergift=UserGifts::where([['user_id',$Agency->user_id],['svga','1678237219.svg']])->delete();
              
              //1678237219.svg
return 'done';
      
  }
  public function AgencymembersUpdate(){

    try {
        $Agency=Agency::all();
        foreach ($Agency as $sku){ 
             $UserApp=UserApp::where("AgencyId",$sku->id)->count();
             $sku->user_number= $UserApp;
              $sku->save();
// Code Here
}
        
 
        return $Agency ;

   if($UserApp!=null){
 
      return $this->returnData('Users', $UserAp );
    }else{
      return $this->returnError('E001', 'Not Found');

    }
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}}
public function CheckAgencyLogin($id,$pass){

    try {

   $Agency=Agency::where([['id',$id],['ban',0],['password',$pass]])->with('user.myvip','Agencypayments.user')->first();
  
   if($Agency!=null){
 
      return $this->returnData('Agency', $Agency );
    }else{
      return $this->returnError('E001', 'Not Found');

    }
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}}
  public function GetAgency(){

    try {

   $Agency=Agency::where('ban',0)->with('user')->orderBy('user_number','DESC')->paginate(10);
   return $this->returnData('Agency', $Agency );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }

 public function GetImportantAgancy(){

    try {

   $Agency=Agency::where([['ban',0],['importance','!=',null]])->with('user')->orderBy('importance','DESC')->get();
   return $this->returnData('Agency', $Agency );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
//--------------------------------------
public function GetAgencyInfo($id){
    try {
   $Agency=Agency::where('id',$id)->with('user.myvip')->first();
   return $this->returnData('Agency', $Agency );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }

 public function GetAgencyInfoWeb($id){
    try {
   $Agency=Agency::where('id',$id)->with('user.myvip','Members','Agencypayments.user')->first();
   return $this->returnData('Agency', $Agency );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
 
  public function UpdateAgencyPassword($id,$password){
    try {
   $Agency=Agency::where('id',$id)->first();
   $Agency->password=$password;
   $Agency->save();
   return $this->returnData('Agency', $Agency );
} catch (\Exception $ex) {
    return $this->returnError($ex->getCode(), $ex->getMessage());
}
 }
 
 public function SearchAgency($tittle){

    $Agency = Agency::where('appid','like','%'.$tittle.'%')->with('user')->orwhere('name','like','%'.$tittle.'%')->where('ban',0)->orderBy('user_number','DESC')->get();
    return $this->returnData('Agency', $Agency );
  }
  public function RemoveUserFromAgency($id){
$user=UserApp::where('id',$id)->first();
$user->AgencyId=null;
$user->AgencyKarisma=0;
$user->save();
if($user){
    return $this->returnData('Agency', $user );
}else{
    return $this->returnError('E001', 'Can\'t remove user');

}
     
  }
  
    public function AgencyTargetsCalculate(){
        
$categories = [
    100000,
    300000,
    600000,
    1000000,
    1600000,
    3000000,
    7000000,
    10000000,
    15000000,
    20000000,
    30000000,
    60000000,
    80000000,
    100000000,
    150000000,
];

$agencies = Agency::with(['Members2' => function($query) {
    $query->select('name', 'myappid', 'AgencyId', 'AgencyKarisma');
}])->get();

$agencyData = [];

foreach ($agencies as $agency) {
    $userCategories = array_fill_keys($categories, []);
    $notCategorizedUsers = [];

    $members = $agency->Members2->where('AgencyId', $agency->id);

    foreach ($members as $user) {
        $userData = [
            'id' => $user->myappid,
            'name' => $user->name,
            'target' => $user->AgencyKarisma
        ];

        $addedToCategory = false;
        foreach (array_reverse($categories) as $category) {
            if ($user->AgencyKarisma >= $category) {
                $userCategories[$category][] = $userData;
                $addedToCategory = true;
                break;
            }
        }

        if (!$addedToCategory) {
            $notCategorizedUsers[] = $userData;
        }
    }

    if (!empty($notCategorizedUsers)) {
        $userCategories['Not categorized'] = $notCategorizedUsers;
    }

    $nonEmptyCategories = array_filter($userCategories, function($users) {
        return count($users) > 0;
    });

    if (!empty($nonEmptyCategories)) {
        $agencyData[] = [
            'agency_id' => $agency->id,
            'agency_name' => $agency->name,
            'agency_id' => $agency->appid,
            'user_categories' => $userCategories
        ];
    }
}

return $agencyData;

  }




  
  
  
}
