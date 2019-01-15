<?php
namespace App\CBModels;

use App\CBRepositories\T4TParticipantRepository;
use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class T4tIdRelation extends Model
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
	* @return T4TParticipantRepository
	*/
	public function getIdPart() {
		return T4TParticipantRepository::findByParticipantID($this->idPart);
	}

	public function setIdPart($idPart) {
		$this->idPart = $idPart;
	}

    /**
     * @return T4TParticipantRepository
     */
	public function getRelatedPart() {
		return T4TParticipantRepository::findByParticipantID($this->relatedPart);
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