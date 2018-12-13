<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/9/2018
 * Time: 10:31 PM
 */

namespace App\CBServices;


use App\CBModels\Users;
use App\CBRepositories\UsersRepository;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersService
{
    public static function logout(): void
    {
        Auth::logout();
    }

    public static function isLoggedIn(): bool
    {
        if(Auth::check()) return true;
        else return false;
    }

    /**
     * @return UsersRepository
     */
    public static function getLoginUserData()
    {
        $user = UsersRepository::findById(Auth::id());
        return $user;
    }

    public static function login(string $email, string $password): bool
    {
        if(Auth::attempt(['email'=> $email, 'password'=> $password])) {
            return true;
        }else{
            return false;
        }
    }

    public static function register(Request $request)
    {

        $photo = FileHelper::uploadFile("photo");

        $user = new Users();
        $user->setRole("Participant");
        $user->setName($request->name);
        $user->setEmail($request->email);
        $user->setPassword(Hash::make($request->password));
        $user->save();


    }
}