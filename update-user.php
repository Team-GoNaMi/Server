<?php

    error_reporting(E_ALL); 
    ini_set("display_errors",1); 

    include("dbcon.php");


    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");


    error_log("TST",0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.

	$id=$_POST["id"];    
//	$password=$_POST["password"];
        $phonenum=$_POST["phonenum"];
        $school=$_POST["school"];

/*
	$no_pw = false;

        if(empty($password)){
            $no_pw = true;
        }
 */
        if (empty($phonenum)){
            $errMSG = "전화번호를 입력하세요.";
        }
        if (empty($school)){
            $errMSG = "학교를 입력하세요.";
        }

        if(!isset($errMSG)) // 모두 입력이 되었다면 
        {
            try{
//		if (!$no_pw) {
		$stmt = $con->prepare("UPDATE member SET phonenum=:phonenum, school=:school WHERE member_id=:id");
                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":phonenum", $phonenum);
                $stmt->bindParam(":school", $school);
/*		
		else {
		// SQL문을 실행하여 데이터를 MySQL 서버의 person 테이블에 저장합니다.
                    $hashed_pw = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $con->prepare("UPDATE member SET password=:hashed_pw, phonenum=:phonenum, school=:school WHERE member_id=:id");
                    $stmt->bindParam(":id", $id);
                    $stmt->bindParam(":hashed_pw", $hashed_pw);
                    $stmt->bindParam(":phonenum", $phonenum);
                    $stmt->bindParam(":school", $school);
		}
 */	
                $stmt->execute();

            } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
            }
        }
       
    }

?>
