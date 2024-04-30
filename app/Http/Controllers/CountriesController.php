<?php

namespace App\Http\Controllers;
use App\Models\Countries;
use App\Models\Rooms;
use App\Models\UserApp;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    function GetCountries(){
        $Countries=Countries::all();
        return   $Countries;
      }
      
       function AddCountries(){
           $Countries=Countries::all()->pluck("name");
           $Rooms = Rooms::whereNotIn('city', $Countries)->get();
               
           foreach ($Rooms as $items) {
               $user=UserApp::where("id",$items->admin_id)->first();
              
               $countr=Countries::where('name' ,$user->city)->first();
                
               if($countr==null){
                     Countries::create([
        "name"=>$user->city,
        "flag"=>$user->Flag,
            ]);
               }
               
       
              }
              
              
           return $Rooms;
           
       }
      

}
