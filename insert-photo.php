<?php
    if(isset($_POST["idx"]) && $_POST["idx"]>=0){
	$register_id=$_POST["register_id"];    
	$idx=$_POST["idx"];
	//		$register_id = $_POST["register_id"];
	$file_dir = "./photos/" .$register_id ."/";

	// 폴더 생성하기
	if (!is_dir($file_dir)) {
	    mkdir($file_dir, 0777);
	}
	$file_path = $file_dir .$idx. ".jpg";//이미지화일명은 인덱스번호
		//$file_path = "";
		//$file_path = $file_path . basename( $_FILES['uploaded_file']['name']);

	if (true) {
	    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
		$result = array("result" => "success");
	    } else{
		$result = array("result" => "error =" .  $_FILES['uploaded_file']['tmp_name'] . "= " . $file_path);
	    }
	}
	else {
	    $result = array("result" => "no tmp_file");
	}
/*
	$file_info = array();
	$file_info["name"] = $_FILES["uploaded_file"]["name"];
	$file_info["type"] = $_FILES["uploaded_file"]["type"];
	$file_info["tmp_name"] = $_FILES["uploaded_file"]["tmp_name"];
	$file_info["size"] = $_FILES["uploaded_file"]["size"];	    
	$file_info["error"] = $_FILES["uploaded_file"]["error"]; 
 	
	$final = array();

	array_push($final, $result);
	array_push($final, $file_info);
 */
	echo json_encode($result);

    }
?>
