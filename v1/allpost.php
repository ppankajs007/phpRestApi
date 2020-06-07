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

if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
		 	if( $dataUser = $post->postAll() ){
		 			$payload_info = [
						"iss"  => 'localhost',
						"iat"  => time(),
						"nbf"  => time() + 10,
						"exp"  => time() + 86400,
						"aud"  => 'myUser',
						"data" => $dataUser
					];

					$secret_key = secret_code;
					$jwtData = JWT::encode($payload_info,$secret_key);
					http_response_code(200);
					echo json_encode([
							'status' => 1,
							'token'  => $jwtData,
							'message' => 'All Data get'
						]);

				}else{
					http_response_code(500);
					echo json_encode([
						'status' => 0,
						'message' => 'Your Password is Worng.Please try again'
					]);
				}

}else{
	http_response_code(400);
	echo json_encode(['status'  => 0,
					  'message' => 'This MEthod Not Exist']);
}



