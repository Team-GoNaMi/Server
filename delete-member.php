<?php

    error_reporting(E_ALL); 

    ini_set("display_errors",1); 

    include("dbcon.php");

    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");

    error_log("TST",0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )

    {

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.

        $member_id=$_POST["member_id"];		

        try{

		$member_stmt = $con->prepare("DELETE FROM member
			WHERE member_id=:member_id");

		$member_stmt->bindParam(":member_id", $member_id);

		$member_stmt->execute();

        } catch(PDOException $e) {

                die("Database error: " . $e->getMessage()); 

        }

       

    }

?>
