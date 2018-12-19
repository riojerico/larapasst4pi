<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\Users;

class UsersRepository extends Users
{
    // TODO : Make you own query methods

    public static function findByEmail(string $email)
    {
        return new static(static::table()->where("email", $email)->first());
    }

    public static function findAllParticipant()
    {
        return static::table()
            ->leftjoin("t4t_t4t.t4t_participant as a","a.no","=","users.t4t_participant_no")
            ->addSelect("a.id as code")
            ->where('role','Participant')->get();
    }

}