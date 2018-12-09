<?php

namespace App\Http\Controllers;

use App\CBRepositories\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\CBServices\UsersService;

class AuthController extends Controller
{

    public function getLogin()
    {
        return view("signin");
    }

    public function getLogout()
    {
        UsersService::logout();

        return redirect()->action("AuthController@getLogin");
    }

    public function postLogin(Request $request)
    {
        try {
            $this->validate($request, [
                "email" => "exists:users",
                "password" => "required"
            ]);

            if(UsersService::login($request->get("email"), $request->get("password"))) {
                return redirect()->action("DashboardController@getIndex");
            }else{
                return redirect()->back()->with(['message'=>'The password you entered was wrong!','message_type'=>'warning']);
            }

        } catch (ValidationException $e) {
            return redirect()->back()->with(['message'=>$e->getMessage(),'message_type'=>'warning']);
        }
    }
}
