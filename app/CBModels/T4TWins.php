<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class T4TWins extends Model
{
    public static $tableName = "t4t_wins";

    public static $connection = "mysql_t4t_t4t";

    
	private $no;
	private $wins;
	private $noOrder;
	private $pesen;
	private $used;
	private $unused;
	private $vc;
	private $bl;
	private $idPart;
	private $noShipment;
	private $time;
	private $logUser;
	private $transType;
	private $relation;
	private $idRetailer;
	private $idApiTrans;


    
	public function getNo() {
		return $this->no;
	}

	public function setNo($no) {
		$this->no = $no;
	}

	public function getWins() {
		return $this->wins;
	}

	public function setWins($wins) {
		$this->wins = $wins;
	}

	public function getNoOrder() {
		return $this->noOrder;
	}

	public function setNoOrder($noOrder) {
		$this->noOrder = $noOrder;
	}

	public function getPesen() {
		return $this->pesen;
	}

	public function setPesen($pesen) {
		$this->pesen = $pesen;
	}

	public function getUsed() {
		return $this->used;
	}

	public function setUsed($used) {
		$this->used = $used;
	}

	public function getUnused() {
		return $this->unused;
	}

	public function setUnused($unused) {
		$this->unused = $unused;
	}

	public function getVc() {
		return $this->vc;
	}

	public function setVc($vc) {
		$this->vc = $vc;
	}

	public function getBl() {
		return $this->bl;
	}

	public function setBl($bl) {
		$this->bl = $bl;
	}


	public function getIdPart() {
		return $this->idPart;
	}

	public function setIdPart($idPart) {
		$this->idPart = $idPart;
	}

	public function getNoShipment() {
		return $this->noShipment;
	}

	public function setNoShipment($noShipment) {
		$this->noShipment = $noShipment;
	}

	public function getTime() {
		return $this->time;
	}

	public function setTime($time) {
		$this->time = $time;
	}

	public function getLogUser() {
		return $this->logUser;
	}

	public function setLogUser($logUser) {
		$this->logUser = $logUser;
	}

	public function getTransType() {
		return $this->transType;
	}

	public function setTransType($transType) {
		$this->transType = $transType;
	}

	public function getRelation() {
		return $this->relation;
	}

	public function setRelation($relation) {
		$this->relation = $relation;
	}


	public function getIdRetailer() {
		return $this->idRetailer;
	}

	public function setIdRetailer($idRetailer) {
		$this->idRetailer = $idRetailer;
	}

	public function getIdApiTrans() {
		return $this->idApiTrans;
	}

	public function setIdApiTrans($idApiTrans) {
		$this->idApiTrans = $idApiTrans;
	}


}