<?php

    error_reporting(E_ALL); 
    ini_set("display_errors",1); 

    include("dbcon.php");
    include ("send-notification.php");
    
//    session_start();

    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");


    error_log("TST",0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.

        $register_id=$_POST["register_id"];
        $buyer_id=$_POST["buyer_id"];
        $school=$_POST["school"];
	
		
        try{
            $trade_stmt = $con->prepare("UPDATE trade SET buyer_id=:buyer_id, state=1 WHERE book_register_id=:register_id");
	    $trade_stmt->bindParam(":buyer_id", $buyer_id);
	    $trade_stmt->bindParam(":register_id", $register_id);
	    $trade_stmt->execute();

	    $school_stmt = $con->prepare("UPDATE book_school SET selected=1 WHERE book_register_id=:register_id AND school=:school");
	    $school_stmt->bindParam(":register_id", $register_id);
	    $school_stmt->bindParam(":school", $school);
	    if ($school_stmt->execute()) {
		echo $register_id . " >> " .$school . ": update to 1";
	    }
	    else {
		echo $school . ": failed to 1";
	    }

	    $buy_avail_stmt = $con->prepare("UPDATE register_book SET buy_avail=0 WHERE book_register_id=:register_id");
	    $buy_avail_stmt->bindParam(":register_id", $register_id);
	    $buy_avail_stmt->execute();

	    // fcm
            $stmt = $con->prepare("SELECT seller_id, Token 
                                        FROM register_book JOIN token ON register_book.seller_id=token.member_id
                                        WHERE book_register_id=:register_id
                                        LIMIT 1");
            $stmt->bindParam(":register_id", $register_id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $seller_token ="";
	    
	    if ($stmt->rowCount() > 0){
                $seller_id = $row["seller_id"];
                $seller_token = $row["Token"];
            }

            $mTitle = "책이 팔렸어요";
            $mMessage = "북박스를 예약해 주세요~!";

            $input_data = array("title" =>$mTitle, "body" => $mMessage);
	    $result = send_notification($seller_token, $input_data);

            echo $result;


        } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
        }
       
    }

?>
