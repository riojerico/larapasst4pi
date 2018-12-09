<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/9/2018
 * Time: 10:31 PM
 */

namespace App\CBServices;


use App\CBRepositories\UsersRepository;
use Illuminate\Support\Facades\Hash;

class UsersService
{
    private static $session_name = "t4t_users_id";

    public static function logout(): void
    {
        session()->forget(self::$session_name);
    }

    /**
     * @return UsersRepository
     */
    public static function getLoginUserData()
    {
        return UsersRepository::findById(session(static::$session_name));
    }

    public static function login(string $email, string $password): bool
    {
        $user = UsersRepository::findByEmail($email);
        if(Hash::check($password, $user->getPassword()))
        {
            session()->put(static::$session_name, $user->getId());
            return true;
        }else{
            return false;
        }
    }
}