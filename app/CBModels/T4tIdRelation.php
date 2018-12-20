<?php
namespace App\CBModels;

use App\CBRepositories\T4tParticipantRepository;
use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class T4tIdrelation extends Model
{
    public static $tableName = "t4t_idrelation";

    public static $connection = "mysql_t4t_t4t";

    
	private $no;
	private $idPart;
	private $relatedPart;
	private $repeatId;


    
	public function getNo() {
		return $this->no;
	}

	public function setNo($no) {
		$this->no = $no;
	}

	/**
	* @return T4tParticipantRepository
	*/
	public function getIdPart() {
		return T4tParticipantRepository::findByParticipantID($this->idPart);
	}

	public function setIdPart($idPart) {
		$this->idPart = $idPart;
	}

    /**
     * @return T4tParticipantRepository
     */
	public function getRelatedPart() {
		return T4tParticipantRepository::findByParticipantID($this->relatedPart);
	}

	public function setRelatedPart($relatedPart) {
		$this->relatedPart = $relatedPart;
	}

	public function getRepeatId() {
		return $this->repeatId;
	}

	public function setRepeatId($repeatId) {
		$this->repeatId = $repeatId;
	}


}