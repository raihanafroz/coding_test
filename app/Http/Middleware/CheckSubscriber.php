<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSubscriber
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
      if (Auth::user()){
        if (\auth()->user()->status == 1){
          return $next($request);
        }
        return redirect()->route('home');
      }
      return redirect()->route('login');
    }
}
