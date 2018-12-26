<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\ApiLogs;

class ApiLogsRepository extends ApiLogs
{
    // TODO : Make you own query methods

    public static function findAllPagination($limit= 20)
    {
        return static::simpleQuery()->orderby('id','desc')->paginate($limit);
    }
}