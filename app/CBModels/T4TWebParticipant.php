<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class T4TWebParticipant extends Model
{
    public static $tableName = "participants";

    public static $connection = "mysql_t4t_web";

    
	private $idPart;
	private $aboveMap;
	private $belowMap;
	private $wincheckText;
	private $refpageText;
	private $startYear;
	private $qtyTrees;
	private $qtyFamilies;


    
	/**
	* @return T4tParticipant
	*/
	public function getIdPart() {
		return T4tParticipant::findById($this->idPart);
	}

	public function setIdPart($idPart) {
		$this->idPart = $idPart;
	}

	public function getAboveMap() {
		return $this->aboveMap;
	}

	public function setAboveMap($aboveMap) {
		$this->aboveMap = $aboveMap;
	}

	public function getBelowMap() {
		return $this->belowMap;
	}

	public function setBelowMap($belowMap) {
		$this->belowMap = $belowMap;
	}

	public function getWincheckText() {
		return $this->wincheckText;
	}

	public function setWincheckText($wincheckText) {
		$this->wincheckText = $wincheckText;
	}

	public function getRefpageText() {
		return $this->refpageText;
	}

	public function setRefpageText($refpageText) {
		$this->refpageText = $refpageText;
	}

	public function getStartYear() {
		return $this->startYear;
	}

	public function setStartYear($startYear) {
		$this->startYear = $startYear;
	}

	public function getQtyTrees() {
		return $this->qtyTrees;
	}

	public function setQtyTrees($qtyTrees) {
		$this->qtyTrees = $qtyTrees;
	}

	public function getQtyFamilies() {
		return $this->qtyFamilies;
	}

	public function setQtyFamilies($qtyFamilies) {
		$this->qtyFamilies = $qtyFamilies;
	}


}