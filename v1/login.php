<?php 
//include header 
ini_set('display_errors', 1);

require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Method: POST');
header('Content-type: application/json; charst=UTF-8');

/* include file */
include_once '../config/database.php';
include_once '../classes/Student.php';
/*function __autoload($class){
	include_once "../classes/$class.php";
}*/

$conn = new Database();
$insertUser = new Student($conn->conn());


if ( $_SERVER['REQUEST_METHOD'] === "POST" ) {

	$data = json_decode(file_get_contents("php://input"));

	if ( !empty($data->email) && !empty($data->password) ) {
		
		$insertUser->email = $data->email;
		if ( !empty($dataUser = $insertUser->emailExist()) ) {

				if ( password_verify($data->password,$dataUser['password']) ) {

					$payload_info = [
						"iss"  => 'localhost',
						"iat"  => time(),
						"nbf"  => time() + 10,
						"exp"  => time() + 86400,
						"aud"  => 'myUser',
						"data" => [ 'status'     => 1,
									'id'         => $dataUser['id'],
									'name'       => $dataUser['name'],
									'mobile'     => $dataUser['mobile'],
									'image'		 => $dataUser['image'],
									'created_at' => $dataUser['created_at']
								],
					];

					$secret_key = "owt123";
					
					$jwtData = JWT::encode($payload_info,$secret_key);

					http_response_code(200);
					echo json_encode([
							'status' => 1,
							'token'  => $jwtData,
							'message' => 'Your have succefully logedin'
						]);

				}else{
					http_response_code(500);
					echo json_encode([
						'status' => 0,
						'message' => 'Your Password is Worng.Please try again'
					]);
				}
		}else{
			http_response_code(500);
			echo json_encode([
				'status' => 0,
				'message' => 'Email Not exist'
				]);
		}

	}else{
		http_response_code(500);
		echo json_encode([
			'status'  => 0,
			'message' => 'All Field Required'
		]);
	}
	
}else{
	http_response_code(500);
	echo json_encode([
		'status'  => 0,
		'message' => 'Method Error' 
	]);
}
