<?php

namespace App\Http\Controllers;

 
use App\Models\emojicategory;
use Illuminate\Http\Request;
use Validator;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
class EmojicategoryController extends Controller
{
    
     use GeneralTrait;
    public function Getemojicategory(){
        
      $emojicategory=  emojicategory::with("emoji")->get();
      return  $emojicategory;
    }
}
