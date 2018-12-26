<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class ApiLogs extends Model
{
    public static $tableName = "api_logs";

    public static $connection = "mysql";

    
	private $id;
	private $createdAt;
	private $updatedAt;
	private $ip;
	private $name;
	private $description;
	private $url;
	private $useragent;
	private $requestData;
	private $responseData;
	private $oldData;
	private $newData;
	private $responseCode;

    /**
     * @return mixed
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * @param mixed $responseData
     */
    public function setResponseData($responseData): void
    {
        $this->responseData = $responseData;
    }



    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }



    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }



    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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

	public function getIp() {
		return $this->ip;
	}

	public function setIp($ip) {
		$this->ip = $ip;
	}

	public function getUseragent() {
		return $this->useragent;
	}

	public function setUseragent($useragent) {
		$this->useragent = $useragent;
	}

	public function getRequestData() {
		return $this->requestData;
	}

	public function setRequestData($requestData) {
		$this->requestData = $requestData;
	}


	public function getOldData() {
		return $this->oldData;
	}

	public function setOldData($oldData) {
		$this->oldData = $oldData;
	}

	public function getNewData() {
		return $this->newData;
	}

	public function setNewData($newData) {
		$this->newData = $newData;
	}

	public function getResponseCode() {
		return $this->responseCode;
	}

	public function setResponseCode($responseCode) {
		$this->responseCode = $responseCode;
	}


}