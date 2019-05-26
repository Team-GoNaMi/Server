<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("dbcon.php");
    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");
    error_log("TST", 0);
    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {
        $id=$_POST["id"];
        $password=$_POST["password"];
	$token=$_POST["token"];

	// 안드로이드 코드의 posParameters 변수에 적어 준 이름을 가지고 값을 전달받습니다.
        if (empty($id))
            $errMSG = "아이디를 입력하세요.";
        else if (empty($password))
	    $errMSG = "비밀번호를 입력하세요.";
	else if (empty($token))
            $errMSG = "토큰이 없습니다.";

        if (!isset($errMSG)) {
            try {
                $stmt = $con->prepare("SELECT * FROM member WHERE member_id=:id LIMIT 1");
                $stmt->bindParam("id", $id);
                $stmt->execute();
                $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
                $response = array();
                $response["success"] = false;
                if ($stmt->rowCount() > 0) {
                    if(password_verify($password, $userRow["password"])) {
//                        $_SESSION['id'] = $userRow['id'];
                   
                        $response["success"] = true;
                        $response["id"] = $id;
			$response["pw"] = $password;
			$response["name"] = $userRow["name"];

			$token_stmt = $con->prepare("UPDATE token SET Token=:token WHERE member_id=:id");
			$token_stmt->bindParam(":token", $token);
			$token_stmt->bindParam(":id", $id);
			$token_stmt->execute();

                    }
		}
                    
                header("Content-Type: application/jason; charset-utf8");
                echo json_encode($response, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
            } catch (PDOException $e) {
                 die("Database error : " .$e.getMessage());
            }
        }
    }
?> 
