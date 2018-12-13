<?php
namespace App\CBModels;

use DB;
use Crocodicstudio\Cbmodel\Core\Model;

class T4tParticipant extends Model
{
    public static $tableName = "t4t_participant";

    public static $connection = "mysql_t4t_t4t";

    
	private $no;
	private $id;
	private $type;
	private $name;
	private $lastname;
	private $comment;
	private $address;
	private $phone;
	private $fax;
	private $director;
	private $pic;
	private $product;
	private $outletQty;
	private $material;
	private $janjian;
	private $dateJoin;
	private $email;
	private $email1;
	private $email2;
	private $website;
	private $introduction;
	private $header;


    
	public function getNo() {
		return $this->no;
	}

	public function setNo($no) {
		$this->no = $no;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getLastname() {
		return $this->lastname;
	}

	public function setLastname($lastname) {
		$this->lastname = $lastname;
	}

	public function getComment() {
		return $this->comment;
	}

	public function setComment($comment) {
		$this->comment = $comment;
	}

	public function getAddress() {
		return $this->address;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public function getPhone() {
		return $this->phone;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}

	public function getFax() {
		return $this->fax;
	}

	public function setFax($fax) {
		$this->fax = $fax;
	}

	public function getDirector() {
		return $this->director;
	}

	public function setDirector($director) {
		$this->director = $director;
	}

	public function getPic() {
		return $this->pic;
	}

	public function setPic($pic) {
		$this->pic = $pic;
	}

	public function getProduct() {
		return $this->product;
	}

	public function setProduct($product) {
		$this->product = $product;
	}

	public function getOutletQty() {
		return $this->outletQty;
	}

	public function setOutletQty($outletQty) {
		$this->outletQty = $outletQty;
	}

	public function getMaterial() {
		return $this->material;
	}

	public function setMaterial($material) {
		$this->material = $material;
	}

	public function getJanjian() {
		return $this->janjian;
	}

	public function setJanjian($janjian) {
		$this->janjian = $janjian;
	}

	public function getDateJoin() {
		return $this->dateJoin;
	}

	public function setDateJoin($dateJoin) {
		$this->dateJoin = $dateJoin;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getEmail1() {
		return $this->email1;
	}

	public function setEmail1($email1) {
		$this->email1 = $email1;
	}

	public function getEmail2() {
		return $this->email2;
	}

	public function setEmail2($email2) {
		$this->email2 = $email2;
	}

	public function getWebsite() {
		return $this->website;
	}

	public function setWebsite($website) {
		$this->website = $website;
	}

	public function getIntroduction() {
		return $this->introduction;
	}

	public function setIntroduction($introduction) {
		$this->introduction = $introduction;
	}

	public function getHeader() {
		return $this->header;
	}

	public function setHeader($header) {
		$this->header = $header;
	}


}