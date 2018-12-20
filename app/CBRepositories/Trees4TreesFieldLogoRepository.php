<?php
namespace App\CBRepositories;

use App\CBModels\Trees4TreesFieldLogo;
use DB;

class Trees4TreesFieldLogoRepository extends Trees4TreesFieldLogo
{
    // TODO : Make you own query methods

    public static function findByEntityId($entity_id)
    {
        return new static(static::simpleQuery()->where('entity_id', $entity_id)->first());
    }

}