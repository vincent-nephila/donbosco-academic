<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class APSA
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
        if(Auth::user()->accesslevel == env('USER_ELEM_APSA')||Auth::user()->accesslevel == env('USER_JHS_APSA')||Auth::user()->accesslevel == env('USER_SHS_APSA')){
          
            return $next($request);
        }else{
            return redirect('/');  
        }
    }
}
