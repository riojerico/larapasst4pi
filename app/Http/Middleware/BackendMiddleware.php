<?php

namespace App\Http\Middleware;

use Closure;

class BackendMiddleware
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
        if(!session()->has("t4t_users_id")) {
            return redirect("auth/login")->with(['message'=>'Please sign in for first!','message_type'=>'warning']);
        }

        return $next($request);
    }
}
