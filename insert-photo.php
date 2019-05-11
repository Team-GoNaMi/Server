<?php
	if(isset($_POST["idx"]) && $_POST["idx"]>=0){
	    $idx=$_POST["idx"];
//		$register_id = $_POST["register_id"];

		$file_path="./photos/".$idx.".jpg";//이미지화일명은 인덱스번호
		//$file_path = "";
		//$file_path = $file_path . basename( $_FILES['uploaded_file']['name']);

//		echo $_FILES["uploaded_file"]["name"] . " + ";
//		echo $_FILES["uploaded_file"]["type"] . " + ";
//		echo $_FILES["uploaded_file"]["tmp_name"] . " + ";
//		echo $_FILES["uploaded_file"]["size"] . " + ";
		
		if (isset($_FILES['uploaded_file']['tmp_name'])) {

			if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
				$result = array("result" => "success");
			} else{
				$result = array("result" => "error - " .  $_FILES['uploaded_file']['tmp_name'] . " - " . $file_path);
			}
			echo json_encode($result);
		}
		else {
			$result = array("result" => "no tmp_file");
		}
	echo json_encode($result);
	}


?>
