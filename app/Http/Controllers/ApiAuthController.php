<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    //

    public function postLogin(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email|exists:users',
                'password' => 'required'
            ]);
            $credential = $request->only(['email','password']);
            if(Auth::attempt($credential)) {
                $user = User::find(Auth::id());
                $token = $user->createToken('Token'.Auth::id())->accessToken;
                return ResponseHelper::responseAPI(200,'success',[
                   'access_token'=>$token
                ]);
            }else{
                return ResponseHelper::responseAPI( 401, 'Email and or password was wrong!');
            }

        } catch (ValidationException $e) {
            return ResponseHelper::responseAPI( 403, $e->getMessage());
        }
    }
}
