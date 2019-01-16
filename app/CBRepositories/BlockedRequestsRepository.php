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
    public static function saveBlocked($request, $key, $requestCount, $status)
    {
        DB::table("blocked_requests")
        ->insert([
            'useragent'=>$request->header('User-Agent'),
            'ip'=>$request->ip(),
            'request_count'=>$requestCount,
            'request_signature'=>$key,
            'status'=>$status
        ]);
    }

    public static function isExistByRequestSignature($signature)
    {
        return static::simpleQuery()
            ->where("request_signature", $signature)
            ->exists();
    }

    public static function isExistPermanentByRequestSignature($signature)
    {
        return static::simpleQuery()
            ->where("request_signature", $signature)
            ->where('status','PERMANENT')
            ->exists();
    }

    public static function findByRequestSignature($key)
    {
        return new static(static::simpleQuery()->where('request_signature', $key)->first());
    }
}