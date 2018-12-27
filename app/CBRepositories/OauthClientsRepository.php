<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\OauthClients;

class OauthClientsRepository extends OauthClients
{
    // TODO : Make you own query methods

    public static function findBySecretKey($key)
    {
        return new static(static::simpleQuery()->where("secret", $key)->first());
    }

    public static function setPasswordGrant($id)
    {
        static::simpleQuery()->where('id',$id)->update(['password_client'=>1]);
    }
}