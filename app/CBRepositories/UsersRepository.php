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
        return static::table()->where('role','Participant')->get();
    }

}