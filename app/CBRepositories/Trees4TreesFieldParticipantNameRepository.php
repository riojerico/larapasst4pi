<?php
namespace App\CBRepositories;

use App\CBModels\Trees4TreesFieldParticipantName;
use DB;

class Trees4TreesFieldParticipantNameRepository extends Trees4TreesFieldParticipantName
{
    // TODO : Make you own query methods

    public static function findByEntityId($entity_id)
    {
        return new static(static::simpleQuery()->where("entity_id", $entity_id)->first());
    }
}