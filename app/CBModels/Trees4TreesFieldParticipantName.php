<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Trees4TreesFieldParticipantName extends Model
{
    public static $tableName = "trees4trees_field_data_field_participant_name";

    public static $connection = "mysql_trees_trees4trees";

    
	private $entityType;
	private $bundle;
	private $deleted;
	private $entityId;
	private $revisionId;
	private $language;
	private $delta;
	private $fieldParticipantNameValue;
	private $fieldParticipantNameFormat;


    
	public function getEntityType() {
		return $this->entityType;
	}

	public function setEntityType($entityType) {
		$this->entityType = $entityType;
	}

	public function getBundle() {
		return $this->bundle;
	}

	public function setBundle($bundle) {
		$this->bundle = $bundle;
	}

	public function getDeleted() {
		return $this->deleted;
	}

	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}

	/**
	* @return Trees4TreesNode
	*/
	public function getEntityId() {
		return Trees4TreesNode::findById($this->entityId);
	}

	public function setEntityId($entityId) {
		$this->entityId = $entityId;
	}


	public function getRevisionId() {
		return $this->revisionId;
	}

	public function setRevisionId($revisionId) {
		$this->revisionId = $revisionId;
	}

	public function getLanguage() {
		return $this->language;
	}

	public function setLanguage($language) {
		$this->language = $language;
	}

	public function getDelta() {
		return $this->delta;
	}

	public function setDelta($delta) {
		$this->delta = $delta;
	}

	public function getFieldParticipantNameValue() {
		return $this->fieldParticipantNameValue;
	}

	public function setFieldParticipantNameValue($fieldParticipantNameValue) {
		$this->fieldParticipantNameValue = $fieldParticipantNameValue;
	}

	public function getFieldParticipantNameFormat() {
		return $this->fieldParticipantNameFormat;
	}

	public function setFieldParticipantNameFormat($fieldParticipantNameFormat) {
		$this->fieldParticipantNameFormat = $fieldParticipantNameFormat;
	}


}