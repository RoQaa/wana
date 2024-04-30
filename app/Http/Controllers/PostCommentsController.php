<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostComments;
use App\Traits\GeneralTrait;
use Validator;
class PostCommentsController extends Controller
{
    use GeneralTrait;
    public function AddComment(Request $request){

        try {
        $rules = [
            "post_id" => "required",
            "user_id" => "required",
            "Comment" => "required",
          ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
   
           $Comment= PostComments::create([
            'post_id'=> $request->post_id,
            'user_id'=> $request->user_id,
            'Comment'=> $request->Comment,
           ]);

 
 $comments= PostComments::where('id',$Comment->id)->with('user')->first();
        if($Comment){ 
            return $this->returnData('Comment',$comments);
            }else{
                return $this->returnError('E001', 'Can\'t add Comment');
             }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function ReplayComment(Request $request){
        try {
            $rules = [
           "Replay"=> "required",
                "Comment_id" => "required",
              ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            };
 
          
     $comments=PostComments::where('id',$request->Comment_id)->first();
 
     $comments->CommentReplay=$request->Replay;
    $comments->save();
            if($comments){ 
                return $this->returnData('Comment',$comments);
                }else{
                    return $this->returnError('E001', 'Can\'t add Comment');
                 }
            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }

    }

    
}
