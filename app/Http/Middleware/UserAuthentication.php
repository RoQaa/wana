<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserApp;
class UserAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
          
        $auth=UserApp::where('id',$request->header('userid'))->first();
       
        
        if($auth->rememper_token==$request->header('Authorization')){
            
             return $next($request);
        }else{
                        return response()->json(['message' => 'Error Accour.'],400);

        }
        
       
       
    }
}
