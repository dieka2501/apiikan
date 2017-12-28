<?php

namespace App\Http\Middleware;

use Closure;
use DB;
class Token
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
        $token = $request->token;
        // dd($token);
        if(is_null($token)) {
           return response()->json(['status'=>false,'data'=>null]);     
        }

        $getToken = DB::table('login')->select('*')->where('token',$token)->first();
        
        if(is_null($getToken)) {
           return response()->json(['status'=>false,'data'=>null]);     
        }
        return $next($request); 
        
    }
}
