<?php 
	
	function send_notification ($token, $data)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		//어떤 형태의 data/notification payload를 사용할것인지에 따라 폰에서 알림의 방식이 달라 질 수 있다.
		$msg = array(
			'title'	=> $data["title"],
			'body' 	=> $data["body"]
          	);

		//data payload로 보내서 앱이 백그라운드이든 포그라운드이든 무조건 알림이 떠도록 하자.
		$fields = array(
			'to'=> $token,
			'priority' => "high",
			'notification'	=> $msg
			);

		//구글키는 config.php에 저장되어 있다.
		$headers = array(
			'Authorization:key =' . "AIzaSyDSpL3-RZodP_r3F1YRY9WZbAgFDlYXF9I",
			'Content-Type: application/json'
			);

	   	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
		$result = curl_exec($ch);           
		
		if (!$result) {
			die('Curl failed: ' . curl_error($ch));
       		}
       		curl_close($ch);
       		return $result;
	}




	error_reporting(E_ALL);
	ini_set("display_errors", 1);
    
	include("../dbcon.php");
//	include ("../insert-buyer.php");
/*
	session_start();
	if (isset($_SESSION["register_id"])) {
		$register_id = $_SESSION["register_id"];
	}
	else {
		die('$'."_SESSION['register_id'] isn't set");
		$register_id = '';
	}
//	echo $register_id;
 */
	$register_id = "20190512235357-1";
	try {
		$stmt = $con->prepare("SELECT seller_id, Token 
					FROM register_book JOIN token ON register_book.seller_id=token.member_id
					WHERE book_register_id=:register_id
					LIMIT 1");
		$stmt->bindParam(":register_id", $register_id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($stmt->rowCount() > 0) {
			$seller_id = $row["seller_id"];
			$seller_token = $row["Token"];
		}

	} catch(PDOException $e) {
		die("Database error : " . $e->getMessage());
	}
	
	$mTitle = "book is sold";
	$mMessage = "please reserve book box";

	$input_data = array("title" =>$mTitle, "body" => $mMessage);

	$result = send_notification($seller_token, $input_data);

	echo $result;
?>

