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
        $name=$_POST["name"];
        $phonenum=$_POST["phonenum"];
        $school=$_POST["school"];

	echo "Test";

        if(empty($id)){
            $errMSG = "아이디를 입력하세요.";
        }
        else if(empty($password)){
            $errMSG = "비밀번호를 입력하세요.";
        }

        else if(empty($name)){
            $errMSG = "이름을 입력하세요.";
        }
        else if (empty($phonenum)){
            $errMSG = "전화번호를 입력하세요.";
        }
        else if (empty($school)){
            $errMSG = "학교를 입력하세요.";
        }

        if(!isset($errMSG)) // 모두 입력이 되었다면 
        {
            try{
                // SQL문을 실행하여 데이터를 MySQL 서버의 person 테이블에 저장합니다.
                $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
		echo $hashed_pw;

                $stmt = $con->prepare("INSERT INTO member(member_id, password, name, phonenum, school) VALUES(:id, :hashed_pw, :name, :phonenum, :school)");
                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":hashed_pw", $hashed_pw);
	        $stmt->bindParam(":name", $name);
                $stmt->bindParam(":phonenum", $phonenum);
                $stmt->bindParam(":school", $school);

                if($stmt->execute())
                {
                    $successMSG = "새로운 사용자를 추가했습니다.";
                }
                else
                {
                    $errMSG = "사용자 추가 에러";
                }

            } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
            }
        }
       
    }

?>
