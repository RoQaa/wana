<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\GeneralTrait;
use App\Models\UserApp;
class AppPassword
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
       
        if( is_null($request->header('awqeASERQW'))||$request->header('awqeASERQW')!='8/325*mAIOEN'){

            return response()->json(['message' => 'Error fUXCK.'],400);
        }

        
        return $next($request);
    }
 
   
}
