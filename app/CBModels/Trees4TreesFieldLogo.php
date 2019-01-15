<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Trees4TreesFieldLogo extends Model
{
    public static $tableName = "trees4trees_field_data_field_logo";

    public static $connection = "mysql_trees_trees4trees";

    
	private $entityType;
	private $bundle;
	private $deleted;
	private $entityId;
	private $revisionId;
	private $language;
	private $delta;
	private $fieldLogoFid;
	private $fieldLogoAlt;
	private $fieldLogoTitle;
	private $fieldLogoWidth;
	private $fieldLogoHeight;


    
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


	public function getEntityId() {
		return $this->entityId;
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

    /**
     * @return Trees4TreesFileManaged
     */
	public function getFieldLogoFid() {
		return Trees4TreesFileManaged::findById($this->fieldLogoFid);
	}

	public function setFieldLogoFid($fieldLogoFid) {
		$this->fieldLogoFid = $fieldLogoFid;
	}

	public function getFieldLogoAlt() {
		return $this->fieldLogoAlt;
	}

	public function setFieldLogoAlt($fieldLogoAlt) {
		$this->fieldLogoAlt = $fieldLogoAlt;
	}

	public function getFieldLogoTitle() {
		return $this->fieldLogoTitle;
	}

	public function setFieldLogoTitle($fieldLogoTitle) {
		$this->fieldLogoTitle = $fieldLogoTitle;
	}

	public function getFieldLogoWidth() {
		return $this->fieldLogoWidth;
	}

	public function setFieldLogoWidth($fieldLogoWidth) {
		$this->fieldLogoWidth = $fieldLogoWidth;
	}

	public function getFieldLogoHeight() {
		return $this->fieldLogoHeight;
	}

	public function setFieldLogoHeight($fieldLogoHeight) {
		$this->fieldLogoHeight = $fieldLogoHeight;
	}


}