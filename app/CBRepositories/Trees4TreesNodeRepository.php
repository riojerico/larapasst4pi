<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\Trees4treesNode;

class Trees4TreesNodeRepository extends Trees4treesNode
{
    // TODO : Make you own query methods

    public static function findByParticipantID($participant_id)
    {
        return new static(static::simpleQuery()->where("title", $participant_id)->first());
    }
}