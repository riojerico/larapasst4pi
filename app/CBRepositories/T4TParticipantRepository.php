<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\T4TParticipant;

class T4TParticipantRepository extends T4TParticipant
{
    // TODO : Make you own query methods

    public static function findByParticipantID($participantId)
    {
        return new static(static::simpleQuery()->where("id", $participantId)->first());
    }

    public static function findByEmail($email)
    {
        return new static(static::simpleQuery()->where('email', $email)->first());
    }
}