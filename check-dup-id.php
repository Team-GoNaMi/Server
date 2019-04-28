<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("dbcon.php");

    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");

    error_log("TST", 0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {

        $id=$_POST["id"];


        // 안드로이드 코드의 posParameters 변수에 적어 준 이름을 가지고 값을 전달받습니다.
        if (empty($id))
            $errMSG = "아이디를 입력하세요.";
        
        if (!isset($errMSG)) {

            try {
                $stmt = $con->prepare("SELECT * FROM member WHERE member_id=:id");
                $stmt->bindParam(":id", $id);
                $stmt->execute();
		
				$response = array();
                $response["success"] = false;
		
				// ID가 같은 게 없으면
                if ($stmt->rowCount() == 0) {
					$response["success"] = true;
					$response["id"] = $id;
                }
                    
				header("Content-Type: application/jason; charset-utf8");
                echo json_encode($response, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);


            } catch (PDOException $e) {
                 die("Database error : " .$e.getMessage());
            }

        }

    }

?>       
