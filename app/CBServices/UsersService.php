<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/9/2018
 * Time: 10:31 PM
 */

namespace App\CBServices;


use App\CBModels\Users;
use App\CBRepositories\T4TParticipantRepository;
use App\CBRepositories\UsersRepository;
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
        //insert to t4t_participant
        $participant = T4TParticipantRepository::findByEmail($request->get("email"));

        if($exist = UsersRepository::findByEmail($request->get("email"))) {
            if($exist->getId()) {
                throw new \Exception("You have already registered before!");
            }
        }

        $user = new Users();
        $user->setRole("Participant");
        $user->setName($participant->getName());
        $user->setEmail($participant->getEmail());
        $user->setT4tParticipantNo($participant->getNo());
        $user->setPassword(Hash::make($request->password));
        $user->save();

    }
}