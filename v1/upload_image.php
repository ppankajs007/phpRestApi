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
	$file = $_FILES['image'];
	$file['name'] = strtolower($_FILES['image']['name']);
	$ext = pathinfo($file['name'],PATHINFO_EXTENSION);
	$fileArray = explode('.', $file['name']);
	//$name = $fileArray[0].time().'.'.$ext;
	$name = $fileArray[0].'.'.$ext;
	$store = "C:xampp/htdocs/laravelreact/resources/js/components/img/profileImage/{$name}";
	if(!empty($file['name']) && $file['error'] == 0){
		if( $file['size'] <= 1000000 ){
			if( $ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' ){
					if( move_uploaded_file($file['tmp_name'],$store) ){
						$post->image = $name;
						$post->user_id = $_POST['myUid'];

						if($post->userImageUpdate()){
							http_response_code(200);
								echo json_encode([
								'status'  => 1,
								'imageName' => $name,
								'message' => 'Image uploaded' 
							]);	
						}else{
							http_response_code(500);
								echo json_encode([
								'status'  => 0,
								'message' => 'Image not uploaded' 
							]);
						}

						
					}else{
						http_response_code(500);
							echo json_encode([
							'status'  => 0,
							'message' => 'Image not uploaded' 
						]);			
					}

			}else{
				http_response_code(500);
				echo json_encode([
				'status'  => 0,
				'message' => 'Image invalid' 
			]);	
			}
		}else{
			http_response_code(500);
				echo json_encode([
				'status'  => 0,
				'message' => 'Image Size too large' 
			]);	
		}

	}else{
		http_response_code(500);
		echo json_encode([
			'status'  => 0,
			'message' => 'Something whats wrong.Please Try again' 
		]);
	}
	
}




