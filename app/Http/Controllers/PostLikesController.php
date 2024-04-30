<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostLikes;
use App\Traits\GeneralTrait;
use Validator;
class PostLikesController extends Controller
{
    use GeneralTrait;
    public function AddLike(Request $request){
      
        try {
        $rules = [
            "post_id" => "required",
            "user_id" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $likstate= PostLikes::where([['user_id', $request->user_id],['post_id', $request->post_id]])->first();
       
                  if($likstate!=null){
       
                   return $this->returnError('E001', 'Can\'t add Like');
                  }
           $Like= PostLikes::create([
            'post_id'=> $request->post_id,
            'user_id'=> $request->user_id,
           ]);
     
           $Likes= PostLikes::where('id', $Like->id)->with('user')->first();
        if($Like){ 
            return $this->returnData('Like',$Likes);
            }else{
                return $this->returnError('E001', 'Can\'t add Like');
             }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }




    
    public function RemoveLike(Request $request){
        $likstate= PostLikes::where([['user_id', $request->user_id],['post_id', $request->post_id]])->first();
        $likstate->delete();
if($likstate){
    return $this->returnData('Like','done');
}else{
    return $this->returnError('179', 'CantRemove');
}

    }


}
