<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class PlantingMaps extends Model
{
    public static $tableName = "planting_maps";

    public static $connection = "mysql_t4t_web";

    
	private $no;
	private $idMapdata;
	private $idPart;
	private $idShipment;
	private $name;
	private $geo;
	private $totalTrees;
	private $species;
	private $area;
	private $village;
	private $district;
	private $municipality;
	private $farmer;
	private $plantingYear;


    
	public function getNo() {
		return $this->no;
	}

	public function setNo($no) {
		$this->no = $no;
	}

	public function getIdMapdata() {
		return $this->idMapdata;
	}

	public function setIdMapdata($idMapdata) {
		$this->idMapdata = $idMapdata;
	}


	public function getIdPart() {
		return $this->idPart;
	}

	public function setIdPart($idPart) {
		$this->idPart = $idPart;
	}

	public function getIdShipment() {
		return $this->idShipment;
	}

	public function setIdShipment($idShipment) {
		$this->idShipment = $idShipment;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getGeo() {
		return $this->geo;
	}

	public function setGeo($geo) {
		$this->geo = $geo;
	}

	public function getTotalTrees() {
		return $this->totalTrees;
	}

	public function setTotalTrees($totalTrees) {
		$this->totalTrees = $totalTrees;
	}

	public function getSpecies() {
		return $this->species;
	}

	public function setSpecies($species) {
		$this->species = $species;
	}

	public function getArea() {
		return $this->area;
	}

	public function setArea($area) {
		$this->area = $area;
	}

	public function getVillage() {
		return $this->village;
	}

	public function setVillage($village) {
		$this->village = $village;
	}

	public function getDistrict() {
		return $this->district;
	}

	public function setDistrict($district) {
		$this->district = $district;
	}

	public function getMunicipality() {
		return $this->municipality;
	}

	public function setMunicipality($municipality) {
		$this->municipality = $municipality;
	}

	public function getFarmer() {
		return $this->farmer;
	}

	public function setFarmer($farmer) {
		$this->farmer = $farmer;
	}

	public function getPlantingYear() {
		return $this->plantingYear;
	}

	public function setPlantingYear($plantingYear) {
		$this->plantingYear = $plantingYear;
	}


}