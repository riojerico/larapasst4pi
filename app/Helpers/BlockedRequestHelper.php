<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/10/2018
 * Time: 8:20 PM
 */

namespace App\Helpers;

use App\CBModels\BlockedRequests;
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
        return $this->hashSignature($route->getDomain(), $request->ip());
    }

    private function hashSignature($domain, $ip) {
        return sha1($domain.'|'.$ip);
    }

    /**
     * @param int $limit
     * @throws \Exception
     */
    public function checkPermanentBlockedRequest($limit = 3)
    {
        $key = $this->requestSignature($this->request);
        $check = BlockedRequestsRepository::findByRequestSignature($key);

        if($check->getStatus() == "PERMANENT") {
            throw new \Exception("PERMANENT_BLOCKED_REQUEST", 429);
        }

        if($check->getRequestCount() > $limit) {
            $update = BlockedRequests::findById($check->getId());
            $update->setStatus("PERMANENT");
            $update->save();

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
            $this->hitBlockedTime();
            throw new \Exception("TEMPORARY_BLOCKED_REQUEST", 429);
        }else{
            $this->blockRequest();
        }
    }

    public function unblockByKey($key)
    {
        Cache::forget($key.':blocked');
        Cache::forget($key.':time');
    }

    public function unblockRequest($ip)
    {
        $key = $this->hashSignature($this->request->route()->getDomain(), $ip);
        Cache::forget($key.':blocked');
        Cache::forget($key.':time');
    }

    public function requestTime()
    {
        $key = $this->requestSignature($this->request);
        $time = Cache::get($key.':time');
        return $time;
    }

    public function checkRequestCount()
    {
        $key = $this->requestSignature($this->request);
        return Cache::get($key.':time');
    }

    public function hitRequest()
    {
        $key = $this->requestSignature($this->request);
        Cache::increment($key.':time',1);
    }

    public function hitBlockedTime()
    {
        $key = $this->requestSignature($this->request);
        $blocked = BlockedRequestsRepository::findByRequestSignature($key);
        if($blocked->getId()) {
            $update = BlockedRequests::findById($blocked->getId());
            $update->setRequestCount($blocked->getRequestCount()+1);
            $update->save();
        }else{
            BlockedRequestsRepository::saveBlocked($this->request, $key, 1,"TEMPORARY");
        }
    }

    public function checkBlockedTime()
    {
        $key = $this->requestSignature($this->request);
        $blocked = BlockedRequestsRepository::findByRequestSignature($key);
        return $blocked->getRequestCount()?:0;
    }

    /**
     * @throws \Exception
     */
    public function blockRequest()
    {
        $key = $this->requestSignature($this->request);
        if($this->checkRequestCount() > 3) {
            Cache::put($key.':blocked', 1,30);
            $this->hitBlockedTime();
            $this->checkBlockedRequest();
        }
    }
}