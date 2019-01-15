<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Trees4TreesNode extends Model
{
    public static $tableName = "trees4trees_node";

    public static $connection = "mysql_trees_trees4trees";

    
	private $nid;
	private $vid;
	private $type;
	private $language;
	private $title;
	private $uid;
	private $status;
	private $created;
	private $changed;
	private $comment;
	private $promote;
	private $sticky;
	private $tnid;
	private $translate;


    
	public function getNid() {
		return $this->nid;
	}

	public function setNid($nid) {
		$this->nid = $nid;
	}

	public function getVid() {
		return $this->vid;
	}

	public function setVid($vid) {
		$this->vid = $vid;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getLanguage() {
		return $this->language;
	}

	public function setLanguage($language) {
		$this->language = $language;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getUid() {
		return $this->uid;
	}

	public function setUid($uid) {
		$this->uid = $uid;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function getCreated() {
		return $this->created;
	}

	public function setCreated($created) {
		$this->created = $created;
	}

	public function getChanged() {
		return $this->changed;
	}

	public function setChanged($changed) {
		$this->changed = $changed;
	}

	public function getComment() {
		return $this->comment;
	}

	public function setComment($comment) {
		$this->comment = $comment;
	}

	public function getPromote() {
		return $this->promote;
	}

	public function setPromote($promote) {
		$this->promote = $promote;
	}

	public function getSticky() {
		return $this->sticky;
	}

	public function setSticky($sticky) {
		$this->sticky = $sticky;
	}

	public function getTnid() {
		return $this->tnid;
	}

	public function setTnid($tnid) {
		$this->tnid = $tnid;
	}

	public function getTranslate() {
		return $this->translate;
	}

	public function setTranslate($translate) {
		$this->translate = $translate;
	}


}