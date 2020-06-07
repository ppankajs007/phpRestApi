<?php 
//include header 

ini_set("display_error", 1);
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Method: POST');
header('Content-type: application/json; charst=UTF-8');

/* include file */
include_once '../config/database.php';
include_once '../config/constant.php';
include_once '../classes/student.php';
/*function __autoload($class){
	include_once "../classes/$class.php";
}*/

$conn = new Database();
$post = new Student($conn->conn());

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	$data = json_decode(file_get_contents("php://input"));
	if ( !empty($data->loginUser) ) {
		$post->user_id = $data->loginUser;
		if( $total_record = $post->userPostRecord() ){
			http_response_code(200);
			echo json_encode([
				'status'       =>  1,
				'total_record' =>  $total_record,
				'message'      =>  'All Recored fetch successfull'
			]);
		}else{
			http_response_code(400);
			echo json_encode(['status'  => 0,
					  'message' => 'This MEthod Not Exist']);
		}
		
	}

}else{
	http_response_code(400);
	echo json_encode(['status'  => 0,
					  'message' => 'This MEthod Not Exist']);
}



