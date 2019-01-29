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
use App\Exceptions\BlockPermanentException;
use App\Exceptions\BlockTemporaryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class BlockedRequestHelper
{

    private $request;
    private $limitToBlockTemporary = 3;
    private $limitToBlockPermanent = 3;
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

    public function checkBlockedRequest()
    {
        $key = $this->requestSignature($this->request);
        $check = BlockedRequestsRepository::findByRequestSignature($key);

        if(env("API_AUTO_BLOCK")===true && $check->getStatus() == "PERMANENT") {
            throw new BlockPermanentException("PERMANENT_BLOCKED_REQUEST", 429);
        }

        if(env("API_AUTO_BLOCK")===true && $check->getRequestCount() > $this->limitToBlockPermanent) {
            $update = BlockedRequests::findById($check->getId());
            $update->setStatus("PERMANENT");
            $update->save();
            throw new BlockPermanentException("PERMANENT_BLOCKED_REQUEST", 429);
        }

        if (env("API_AUTO_BLOCK")===true && $check->getStatus() == "TEMPORARY") {
            $this->increaseTemporary($check->getId());
            throw new BlockTemporaryException("TEMPORARY_BLOCKED_REQUEST", 429);
        }
    }

    private function increaseTemporary($id)
    {
        $update = BlockedRequests::findById($id);
        $update->setRequestCount($update->getRequestCount()+1);
        $update->save();
    }

    public function unblockByKey($key)
    {
        Cache::forget($key.':blocked');
        Cache::forget($key.':time');
    }

    private function checkRequestCount()
    {
        $key = $this->requestSignature($this->request);
        return Cache::get($key.':time');
    }

    private function hitRequest()
    {
        $key = $this->requestSignature($this->request);
        Cache::increment($key.':time', 1);
    }

    public function hitBlockedTime()
    {
        $this->hitRequest();
        $key = $this->requestSignature($this->request);
        if($this->checkRequestCount()>$this->limitToBlockTemporary) {
            BlockedRequestsRepository::saveBlocked($this->request, $key, 1,"TEMPORARY");
        }
    }
}