<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Users extends Model
{
    public static $tableName = "users";

    
	private $id;
	private $name;
	private $email;
	private $emailVerifiedAt;
	private $password;
	private $rememberToken;
	private $createdAt;
	private $updatedAt;
	private $role;
	private $photo;
	private $t4tParticipant;


    
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getEmailVerifiedAt() {
		return $this->emailVerifiedAt;
	}

	public function setEmailVerifiedAt($emailVerifiedAt) {
		$this->emailVerifiedAt = $emailVerifiedAt;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function getRememberToken() {
		return $this->rememberToken;
	}

	public function setRememberToken($rememberToken) {
		$this->rememberToken = $rememberToken;
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

	public function getRole() {
		return $this->role;
	}

	public function setRole($role) {
		$this->role = $role;
	}

	public function getPhoto() {
		return $this->photo;
	}

	public function setPhoto($photo) {
		$this->photo = $photo;
	}

	/**
	* @return T4tParticipant
	*/
	public function getT4tParticipant() {
		return T4tParticipant::findById($this->t4tParticipant);
	}

	public function setT4tParticipant($t4tParticipant) {
		$this->t4tParticipant = $t4tParticipant;
	}


}