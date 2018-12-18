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

    private function deniedMessage()
    {
        return redirect()->back()->with(['message'=>'You did not have any access to this area!','message_type'=>'warning']);
    }

    private function isSuperadmin()
    {
        if(auth()->user()->role == "Superadmin") {
            return true;
        }else{
            return false;
        }
    }

    public function handle($request, Closure $next)
    {
        if(!UsersService::isLoggedIn()) {
            return redirect()->action("AuthController@getLogin")->with(['message'=>'Please sign in for first!','message_type'=>'warning']);
        }

        if($request->is(config('app.admin_path').'/manage-participant*')) {
            if(!$this->isSuperadmin()) return $this->deniedMessage();
        }

        return $next($request);
    }
}
