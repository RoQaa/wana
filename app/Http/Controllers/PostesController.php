<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Validator;
use App\Models\Postes;
use App\Models\PostLikes;
class PostesController extends Controller
{
    use GeneralTrait;
    public function AddPost(Request $request)
    {
        try {
    
        $rules = [
            'user_id' => 'required',
            'content' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $image=null;
        if($request->hasfile('image')){
            $fileName =time().'.png';   
            $file1 = $request->image->move(public_path('images'),$fileName);
            $image=$fileName;
           }

        $Post = Postes::create([
                'user_id' => $request->user_id,
                'content'=> $request->content,
                'image'=> $image,
              
            ]);
            return $this->returnData('Postes', $Post);


    } catch (\Exception $ex) {
 
         return $this->returnError($ex->getCode(), $ex->getMessage());
    }

    }


    public function GetPosts($id){
$userposts=PostLikes::where('user_id',$id)->get();


        $Postes=Postes::where('status',1)->orderBy('id','DESC')->with('user','like.user','commentsuser.user')->paginate(6);
 
        return $this->returnData('Postes',['date'=>$Postes
        ,'posts'=>  $userposts->pluck('post_id')
        ] );
    }
    

    public function GetMyPosts($id){
                $Postes=Postes::where('user_id',$id)->with('user','like.user','commentsuser.user')->get();
              
                return $this->returnData('Postes',$Postes  );
            }
            public function Deletemypost($id){
                $Postes=Postes::where('id',$id)->first();
                if($Postes!=null){
                    $Postes->delete();
                                 
                return $this->returnData('Postes','done' );
                }else{
                    return $this->returnError('156', 'cant delete');
                }

            }

}
