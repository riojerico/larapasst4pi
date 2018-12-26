<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class OauthClients extends Model
{
    public static $tableName = "oauth_clients";

    public static $connection = "mysql";

    
	private $id;
	private $userId;
	private $name;
	private $secret;
	private $redirect;
	private $personalAccessClient;
	private $passwordClient;
	private $revoked;
	private $createdAt;
	private $updatedAt;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	/**
	* @return User
	*/
	public function getUserId() {
		return User::findById($this->userId);
	}

	public function setUserId($userId) {
		$this->userId = $userId;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getSecret() {
		return $this->secret;
	}

	public function setSecret($secret) {
		$this->secret = $secret;
	}

	public function getRedirect() {
		return $this->redirect;
	}

	public function setRedirect($redirect) {
		$this->redirect = $redirect;
	}

	public function getPersonalAccessClient() {
		return $this->personalAccessClient;
	}

	public function setPersonalAccessClient($personalAccessClient) {
		$this->personalAccessClient = $personalAccessClient;
	}

	public function getPasswordClient() {
		return $this->passwordClient;
	}

	public function setPasswordClient($passwordClient) {
		$this->passwordClient = $passwordClient;
	}

	public function getRevoked() {
		return $this->revoked;
	}

	public function setRevoked($revoked) {
		$this->revoked = $revoked;
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


}