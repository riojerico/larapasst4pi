<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/10/2018
 * Time: 8:20 PM
 */

namespace App\Helpers;

use App\CBRepositories\BlockedRequestsRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class BlockedRequestHelper
{

    private $request;
    /**
     * BlockedRequestHelper constructor.
     * @param Request $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @param Request $request
     * @return string
     */
    private function requestSignature($request)
    {
        $route = $request->route();
        return sha1($route->getDomain().'|'.$request->ip());
    }

    /**
     * @param int $limit
     * @throws \Exception
     */
    public function checkPermanentBlockedRequest($limit = 3)
    {
        $key = $this->requestSignature($this->request);

        if($this->checkBlockedTime() > $limit) {
            BlockedRequestsRepository::saveBlocked($this->request, $key, $this->checkBlockedTime());
        }

        $key = $this->requestSignature($this->request);
        if(BlockedRequestsRepository::isExistByRequestSignature($key)) {
            throw new \Exception("PERMANENT_BLOCKED_REQUEST", 429);
        }
    }

    /**
     * @throws \Exception
     */
    public function checkBlockedRequest()
    {
        $key = $this->requestSignature($this->request);
        if(Cache::has($key.':blocked')) {
            throw new \Exception("TEMPORARY_BLOCKED_REQUEST", 429);
        }else{
            $this->blockRequest();
        }
    }

    public function requestTime()
    {
        $key = $this->requestSignature($this->request);
        $time = Cache::get($key.':time');
        return $time;
    }

    public function hitRequest()
    {
        $key = $this->requestSignature($this->request);
        Cache::increment($key.':time',1);
    }

    public function hitBlockedTime()
    {
        $key = $this->requestSignature($this->request);
        Cache::increment($key.':blocked_time',1);
    }

    public function checkBlockedTime()
    {
        $key = $this->requestSignature($this->request);
        return Cache::get($key.':blocked_time');
    }

    /**
     * @throws \Exception
     */
    public function blockRequest()
    {
        $key = $this->requestSignature($this->request);
        if($this->requestTime() > 3) {
            Cache::put($key.':blocked', 1,30);
            $this->hitBlockedTime();
            $this->checkBlockedRequest();
        }
    }
}