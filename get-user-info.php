<?php

    error_reporting(E_ALL); 
    ini_set("display_errors",1); 

    include("dbcon.php");


    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");


    error_log("TST",0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.

        $id=$_POST["user_id"];

        if(empty($id)){
            $errMSG = "아이디를 넘겨주세요";
        }


        if(!isset($errMSG)) // 모두 입력이 되었다면 
        {
            try{
                // SQL문을 실행하여 데이터를 MySQL 서버의 person 테이블에 저장합니다.

                $stmt = $con->prepare("SELECT * FROM  member WHERE member_id=:id LIMIT 1");
                $stmt->bindParam(":id", $id);

		$stmt->execute();
		$memberInfoRow = $stmt->fetch(PDO::FETCH_ASSOC);

		$member_data = array();
		$member_data["success"] = false;

		if ($stmt->rowCount() > 0) {
		    $member_data["success"] = true;
		    $member_data["id"] = $memberInfoRow["member_id"];
		    $member_data["name"] = $memberInfoRow["name"];
		    $member_data["phonenum"] = $memberInfoRow["phonenum"];
		    $member_data["school"] = $memberInfoRow["school"];

		}
		echo json_encode($member_data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

            } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
            }
        }
       
    }

?>
