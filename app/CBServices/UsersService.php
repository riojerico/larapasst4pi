<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/9/2018
 * Time: 10:31 PM
 */

namespace App\CBServices;


use App\CBModels\T4tParticipant;
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

        //insert to t4t_participant
        $participant = new T4tParticipant();
        $participant->setId("EU".str_pad(T4tParticipant::getMaxId()+1,8,0, STR_PAD_LEFT));
        $participant->setName($request->get("name"));
        $participant->setComment($request->get("comment"));
        $participant->setAddress($request->get("address"));
        $participant->setPhone("000");
        $participant->setFax("000");
        $participant->setDirector("NA");
        $participant->setPic("NA");
        $participant->setProduct("NA");
        $participant->setMaterial("NA");
        $participant->setOutletQty(0);
        $participant->setLastname($request->get("lastname"));
        $participant->setEmail($request->get("email"));
        $participant->setDateJoin(date("Y-m-d H:i:s"));
        $participant->setType("Donor");
        $participant->save();

        $user = new Users();
        $user->setRole("Participant");
        $user->setName($request->name);
        $user->setEmail($request->email);
        $user->setPhoto($photo);
        $user->setT4tParticipantNo($participant->getNo());
        $user->setPassword(Hash::make($request->password));
        $user->save();

    }
}