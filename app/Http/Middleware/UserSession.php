<?php

namespace App\Http\Middleware;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Session;
use Auth;
class userSession
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    
    
     public function handle($request, Closure $next)
     {
         // Check if the user is authenticated
         if (!$request->user()) {
             return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
         }
 
         return $next($request);
     }
}
