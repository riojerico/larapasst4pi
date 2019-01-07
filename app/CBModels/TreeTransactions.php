<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class TreeTransactions extends Model
{
    public static $tableName = "tree_transactions";

    public static $connection = "mysql";

    
	private $id;
	private $createdAt;
	private $updatedAt;
	private $noTransaction;
	private $quantity;
	private $idPohon;
	private $email;
	private $idPartFrom;
	private $idPartTo;


    
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

	public function getNoTransaction() {
		return $this->noTransaction;
	}

	public function setNoTransaction($noTransaction) {
		$this->noTransaction = $noTransaction;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	/**
	* @return T4tPohon
	*/
	public function getIdPohon() {
		return T4tPohon::findById($this->idPohon);
	}

	public function setIdPohon($idPohon) {
		$this->idPohon = $idPohon;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	* @return T4tParticipant
	*/
	public function getIdPartFrom() {
		return T4tParticipant::findById($this->idPartFrom);
	}

	public function setIdPartFrom($idPartFrom) {
		$this->idPartFrom = $idPartFrom;
	}

	/**
	* @return T4tParticipant
	*/
	public function getIdPartTo() {
		return T4tParticipant::findById($this->idPartTo);
	}

	public function setIdPartTo($idPartTo) {
		$this->idPartTo = $idPartTo;
	}


}