<?php
namespace App\CBRepositories;

use DB;
use App\CBModels\BlockedRequests;
use Illuminate\Http\Request;

class BlockedRequestsRepository extends BlockedRequests
{
    // TODO : Make you own query methods

    public static function findAllPagination($limit=20)
    {
        return static::simpleQuery()->orderby('id','desc')->paginate($limit);
    }

    /**
     * @param Request $request
     * @param int $requestCount
     */
    public static function saveBlocked($request, $key, $requestCount)
    {
        $new = new static();
        $new->setUseragent($request->header('User-Agent'));
        $new->setIp($request->ip());
        $new->setRequestCount($requestCount);
        $new->setRequestSignature($key);
        $new->save();
    }

    public static function isExistByRequestSignature($signature)
    {
        return static::simpleQuery()->where("request_signature")->exists();
    }

    public static function findByRequestSignature($key)
    {
        return new static(static::simpleQuery()->where('request_signature', $key)->first());
    }
}