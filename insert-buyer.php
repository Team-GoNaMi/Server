<?php

    error_reporting(E_ALL); 
    ini_set("display_errors",1); 

    include("dbcon.php");
    
    session_start();

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
			$school_stmt->execute();

			$buy_avail_stmt = $con->prepare("UPDATE register_book SET buy_avail=0 WHERE book_register_id=:register_id");
			$buy_avail_stmt->bindParam(":register_id", $register_id);
			$buy_avail_stmt->execute();
			
			$_SESSION["register_id"] = $register_id;
			exec('/var/www/html/fcm.send-notification.php');

        } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
        }
       
    }

?>
