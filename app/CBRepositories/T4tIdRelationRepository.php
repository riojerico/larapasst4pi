<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\T4tIdrelation;

class T4tIdrelationRepository extends T4tIdrelation
{
    // TODO : Make you own query methods

    public static function getNewRepeatId($id_part)
    {
        return static::simpleQuery()->where('id_part',$id_part)->count() + 1;
    }
}