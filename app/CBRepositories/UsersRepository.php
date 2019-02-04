<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\Users;

class UsersRepository extends Users
{
    // TODO : Make you own query methods

    public static function findAllAdminPagination($limit = 20)
    {
        return static::simpleQuery()->where('role','Superadmin')->orderby('id','desc')->paginate($limit);
    }

    public static function findByEmail(string $email)
    {
        return new static(static::table()->where("email", $email)->first());
    }

    public static function findAllParticipant()
    {
        return static::table()
            ->leftjoin(env('DB_T4T_T4T').".t4t_participant as a","a.no","=","users.t4t_participant_no")
            ->addSelect("a.id as code")
            ->where('role','Participant')->get();
    }

}