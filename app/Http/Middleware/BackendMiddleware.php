<?php

namespace App\Http\Middleware;

use App\CBServices\UsersService;
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
        if(!UsersService::isLoggedIn()) {
            return redirect()->action("AuthController@getLogin")->with(['message'=>'Please sign in for first!','message_type'=>'warning']);
        }

        return $next($request);
    }
}
