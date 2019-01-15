<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class Trees4TreesFileManaged extends Model
{
    public static $tableName = "trees4trees_file_managed";

    public static $connection = "mysql_trees_trees4trees";

    
	private $fid;
	private $uid;
	private $filename;
	private $uri;
	private $filemime;
	private $filesize;
	private $status;
	private $timestamp;
	private $type;


    
	public function getFid() {
		return $this->fid;
	}

	public function setFid($fid) {
		$this->fid = $fid;
	}

	public function getUid() {
		return $this->uid;
	}

	public function setUid($uid) {
		$this->uid = $uid;
	}

	public function getFilename() {
		return $this->filename;
	}

	public function setFilename($filename) {
		$this->filename = $filename;
	}

	public function getUri() {
		return $this->uri;
	}

	public function setUri($uri) {
		$this->uri = $uri;
	}

	public function getFilemime() {
		return $this->filemime;
	}

	public function setFilemime($filemime) {
		$this->filemime = $filemime;
	}

	public function getFilesize() {
		return $this->filesize;
	}

	public function setFilesize($filesize) {
		$this->filesize = $filesize;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}


}