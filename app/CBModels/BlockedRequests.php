<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class BlockedRequests extends Model
{
    public static $tableName = "blocked_requests";

    public static $connection = "mysql";

    
	private $id;
	private $createdAt;
	private $updatedAt;
	private $useragent;
	private $ip;
	private $requestCount;
	private $requestSignature;
	private $status;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }


    /**
     * @return mixed
     */
    public function getRequestSignature()
    {
        return $this->requestSignature;
    }

    /**
     * @param mixed $requestSignature
     */
    public function setRequestSignature($requestSignature): void
    {
        $this->requestSignature = $requestSignature;
    }

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}

	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
	}

	public function getUpdatedAt() {
		return $this->updatedAt;
	}

	public function setUpdatedAt($updatedAt) {
		$this->updatedAt = $updatedAt;
	}

	public function getUseragent() {
		return $this->useragent;
	}

	public function setUseragent($useragent) {
		$this->useragent = $useragent;
	}

	public function getIp() {
		return $this->ip;
	}

	public function setIp($ip) {
		$this->ip = $ip;
	}

	public function getRequestCount() {
		return $this->requestCount;
	}

	public function setRequestCount($requestCount) {
		$this->requestCount = $requestCount;
	}


}