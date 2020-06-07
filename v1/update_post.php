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
	$post->post = $data->textarea;
	$post->post_id = $data->postId;
	$post->user_id = $data->userId;
	if( $post->userUpdatePost() ){
		http_response_code(200);
		echo json_encode([
			'status' => 1,
			'message' => 'Your Post is Updated'
		]);
	}

}




