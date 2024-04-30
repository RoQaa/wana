<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\VipCenter;
use App\Traits\GeneralTrait;
class VipCenterController extends Controller
{
    use GeneralTrait;
    public function AddVip(Request $request){
        try {
        $rules = [
            "Gif" => "required",
            "name"=> "required",
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $Gif=null;
        if($request->hasfile('Gif')){
            $fileName =time().'.gif';   
            $file1 = $request->Gif->move(public_path('images'),$fileName);
            $Gif=$fileName;
           }
           $vipicon=null;
           if($request->hasfile('vipicon')){
               $fileName =time().'4.png';   
               $file1 = $request->vipicon->move(public_path('images'),$fileName);
               $vipicon=$fileName;
              }
           $Entry=null;
           if($request->hasfile('Entry')){
               $fileName =time().'2.svga';   
               $file1 = $request->Entry->move(public_path('images'),$fileName);
               $Entry=$fileName;
              }
              $Frame=null;
              if($request->hasfile('Frame')){
                  $fileName =time().'1.svga';   
                  $file1 = $request->Frame->move(public_path('images'),$fileName);
                  $Frame=$fileName;
                 }
                 $ProfileEntry=null;
                 if($request->hasfile('ProfileEntry')){
                     $fileName =time().'0.svga';   
                     $file1 = $request->ProfileEntry->move(public_path('images'),$fileName);
                     $ProfileEntry=$fileName;
                    }
           $Vip = VipCenter::create([
            'vipicon'=> $vipicon,
            'Gif'=> $Gif,
            'name'=> $request->name,
            'Entry'=>  $Entry,
            'Frame'=> $Frame,
            'Level'=> $request->Level,
            'SpecialID'=> $request->SpecialID,
            'ProfileEntry'=> $ProfileEntry,
            'ColoredMessage'=> $request->ColoredMessage,
            'Hidden'=> $request->Hidden,
            'cost'=> $request->cost,
            'days'=> $request->days,
        ]);
        if($Vip){
            return $this->returnData('Vip',$Vip);
            }else{
                return $this->returnError('E001', 'Can\'t add Vip');
         }  
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    //-------------------------------------------
    public function GetVip(){
        $VipCenter=VipCenter::where('status',1)->get();
        return $this->returnData('Vip',$VipCenter);
                
        }}
