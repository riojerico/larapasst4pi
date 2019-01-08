<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class T4tPohon extends Model
{
    public static $tableName = "t4t_pohon";

    public static $connection = "mysql_t4t_t4t";

    
	private $idPohon;
	private $namaPohon;
	private $namaLatin;
	private $jarakTanam;



	public function getIdPohon() {
		return $this->idPohon;
	}

	public function setIdPohon($idPohon) {
		$this->idPohon = $idPohon;
	}

	public function getNamaPohon() {
		return $this->namaPohon;
	}

	public function setNamaPohon($namaPohon) {
		$this->namaPohon = $namaPohon;
	}

	public function getNamaLatin() {
		return $this->namaLatin;
	}

	public function setNamaLatin($namaLatin) {
		$this->namaLatin = $namaLatin;
	}

	public function getJarakTanam() {
		return $this->jarakTanam;
	}

	public function setJarakTanam($jarakTanam) {
		$this->jarakTanam = $jarakTanam;
	}


}