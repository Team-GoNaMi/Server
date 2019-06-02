<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("../dbcon.php");
    include ("../send-notification.php");

    error_log("TST", 0);


    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $register_id = $_POST["register_id"];
	$role = $_POST["role"];
	echo $register_id;
	echo $role;
	echo gettype($role);

	try {
	    if ($role == "True") {
		$update_stmt = $con->prepare("UPDATE trade SET state=3 WHERE book_register_id=:register_id");
	        echo "seller : ";
	    }
	    else {
		$update_stmt = $con->prepare("UPDATE trade SET state=4 WHERE book_register_id=:register_id");
	        echo "buyer : ";
	    }

	    $update_stmt->bindParam(":register_id", $register_id);
//	    $update_stmt->execute();
	    if ($update_stmt->execute())
		echo "success to update";
	    else
		echo "failed to update";

	    echo ($update_stmt->rowCount() > 0 ) ? "Success update" : "No rows update";
	    // fcm
	    $push_stmt = $con->prepare("SELECT buyer_id, Token 
		    			FROM trade JOIN token ON trade.buyer_id=token.member_id 
					WHERE book_register_id=:register_id LIMIT 1");
            $push_stmt->bindParam(":register_id", $register_id);
	    $push_stmt->execute();

	    $row = $push_stmt->fetch(PDO::FETCH_ASSOC);
	    $buyer_token = "";

	    if ($push_stmt->rowCount() > 0) {
	        $buyer_id = $row["buyer_id"];
		$buyer_token = $row["Token"];
	    }

	    $mTitle = "";
	    $mMessage = "";


	    if ($role == "True") {    // 판매자가 책을 넣음 -> 구매자에게 책 가져가세욤

                $mTitle = "북박스에 책이 배달되었습니다.";
                $mMessage = "북박스에서 책을 가져가 주세요~!";
	    }
	    else {    // 구매자가 책을 가져감 -> 구매자에게 구매확정을 해 주세요.

                $mTitle = "구매확정";
                $mMessage = "구매를 확정해 주세요~!";

	    }

	    $input_data = array("title" =>$mTitle, "body" => $mMessage);
            $result = send_notification($buyer_token, $input_data);

	    echo $result;
/*
	    if (!$role) {
		// 10분 지연    
		sleep(600);
                // 자동으로 구매 확정으로...!!
		exec();
	    }
 */
 

	}catch (PDOException $e) {
            die("Database error : " .$e->getMessage());
        }
 
    } 
?> 
