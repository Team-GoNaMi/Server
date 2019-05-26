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
	$password=$_POST["password"];


        if (empty($id)){
            $errMSG = "아이디를 입력하세요.";
        }
        if (empty($password)){
            $errMSG = "비밀번호를 입력하세요.";
        }

        if(!isset($errMSG)) // 모두 입력이 되었다면 
        {
		
	    try{
	
		$hashed_pw = password_hash($password, PASSWORD_DEFAULT);	
		$stmt = $con->prepare("UPDATE member SET password=:hashed_pw WHERE member_id=:id");
                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":hashed_pw", $hashed_pw);
                $stmt->execute();
 
            } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
            }
        }
      
    }

?>
