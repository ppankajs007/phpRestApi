<?php 

/**
 * 
 */
class Student
{
		public $name;
		public $mobile;
		public $email;
		public $password;
		private $tbl;
		private $conn;

		/* insert post start */

			public $user_id;
			public $post;
			public $post_id;
			public $img;
			public $offestPagePost;
			public $totalRowPost;
			private $post_table;


		/* insert post end */

	function __construct($db){
		$this->tbl  =  'users';
		$this->post_table = 'user_post';
		$this->conn = $db; 
	}

	function createUser(){
		$insertData = "INSERT INTO $this->tbl SET name = ?,mobile = ?,email = ?,password = ?";
		$userData = $this->conn->prepare($insertData);
		$userData->bind_param('siss',$this->name,$this->mobile,$this->email,$this->password);
		if( $userData->execute() ){
			return true;
		}
		return false;

	}

	function emailExist(){
		$checkEmail = "SELECT * FROM $this->tbl WHERE email = ?";
		$emailPrepare = $this->conn->prepare($checkEmail);
		$emailPrepare->bind_param('s',$this->email);
		if ( $emailPrepare->execute() ) {
			$data = $emailPrepare->get_result();
			return $data->fetch_assoc();
		}
		return [];

	}

	function addPost(){
		$insert   = "INSERT INTO $this->post_table SET user_id = ?,post=?";
		$tablePre = $this->conn->prepare($insert);
		$tablePre->bind_param('ss',$this->user_id,$this->post);
		if($tablePre->execute()){
			return $tablePre->insert_id;
		}
		return false;
	}

	function postBySelect(){
			$select = "SELECT $this->post_table.*,$this->tbl.*,$this->post_table.created_at as postData FROM $this->post_table LEFT JOIN $this->tbl on $this->tbl.id = $this->post_table.user_id WHERE post_id = ?";
			$selectPre = $this->conn->prepare($select);
			$selectPre->bind_param('i',$this->post_id);
			if( $selectPre->execute() ){
				$data = $selectPre->get_result();
				return $data->fetch_assoc();
			}
			return false;
	}

	function postByuser(){
			$select = "SELECT $this->post_table.*,$this->tbl.*,$this->post_table.created_at as postData FROM $this->post_table LEFT JOIN $this->tbl on $this->tbl.id = $this->post_table.user_id  WHERE user_id = ? Order by post_id DESC Limit $this->offestPagePost,$this->totalRowPost";
			$selectPre = $this->conn->prepare($select);
			$selectPre->bind_param('i',$this->user_id);
			if( $selectPre->execute() ){
				$data = $selectPre->get_result();
				return mysqli_fetch_all($data, MYSQLI_ASSOC);
			}
			return false;
	}

	function userImageUpdate(){
		$update = "UPDATE $this->tbl SET image = ? Where id = ?";
		$updateData = $this->conn->prepare($update);
		$updateData->bind_param('si',$this->image,$this->user_id);
		if( $updateData->execute() ){
			return true;
		}
		return false;
	}

	function userDeletePost(){
		$delete = "DELETE FROM $this->post_table WHERE user_id = ? AND post_id = ?";
		$deleteData = $this->conn->prepare($delete);
		$deleteData->bind_param('ss',$this->user_id,$this->post_id);
		if( $deleteData->execute() ){
			return true;
		}
		return false;
	}

	function userUpdatePost(){
		$update = "UPDATE $this->post_table SET post = ? Where post_id = ? AND user_id = ?";
		$updateData = $this->conn->prepare($update);
		$updateData->bind_param('sii',$this->post,$this->post_id,$this->user_id);
		if( $updateData->execute() ){
			return true;
		}
		return false;	
	}

	function postAll(){
			$sql = "SELECT $this->post_table.*,$this->tbl.*,$this->post_table.created_at as postData FROM $this->post_table LEFT JOIN $this->tbl on $this->tbl.id = $this->post_table.user_id Order by post_id DESC";

			$result = $this->conn->query($sql);
			return $result->fetch_all(MYSQLI_ASSOC);

	}

	function userPostRecord(){
		$sql = "SELECT * FROM $this->post_table WHERE user_id = $this->user_id";
		$totalRecord = $this->conn->query($sql);
		if ( $totalRow = mysqli_num_rows($totalRecord) > 0 ) {
			return mysqli_num_rows($totalRecord);
		}
		return false;
	}
}











