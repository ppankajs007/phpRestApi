<?php 
//include header 

ini_set("display_error", 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Method: POST');
header('Content-type: application/json; charst=UTF-8');

/* include file */
include_once '../config/database.php';
function __autoload($class){
	include_once "../classes/$class.php";
}

$conn = new Database();
$insertUser = new Student($conn->conn());

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	$data = json_decode(file_get_contents("php://input"));

	if ( !empty($data->name) && !empty($data->mobile) && !empty($data->email) && !empty($data->password) ) {
		
		$insertUser->name = $data->name;
		$insertUser->mobile = $data->mobile;
		$insertUser->email = $data->email;
		$insertUser->password = password_hash($data->password, PASSWORD_DEFAULT);

		if ( empty($insertUser->emailExist()) ) {

			if( $insertUser->createUser() ){
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"message" => "Inserted Data"
				));
			}else{
				http_response_code(500);
				echo json_encode(array(
					'status' => 0,
					'message' => "try again"
				));	
			}
		}else{

			http_response_code(409);
			echo json_encode([
				'status' => 0,
				'exist'  => $data->email,
				'message' => 'Email already exist.Please try another email'
			]);
		}
	}else{
		http_response_code(500);
		echo json_encode(array(
			'status' => 0,
			'message' => "All Field Required"
		));
	}
}else{
	http_response_code(503);
	echo json_encode(array(
		'status' => 0,
		'message' => "Method Error"
	));
}

