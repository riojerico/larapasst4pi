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

    public function getRegister()
    {
        return view("register");
    }

    public function postRegister(Request $request)
    {
        try {
            $this->validate($request, [
                "name"=>"required|min:1|max:99",
                "lastname"=>"required|min:1|max:99",
                "photo"=>"required|image",
                "email" => "unique:users|email",
                "password" => "required"
            ]);

            UsersService::register($request);

            return redirect()->action("AuthController@getLogin")
                ->with(['message'=>"Your registration has been success, you can now login!","message_type"=>"success"]);

        } catch (ValidationException $e) {
            return redirect()->back()->with(['message'=>$e->getMessage(),'message_type'=>'warning']);
        }
    }
}
