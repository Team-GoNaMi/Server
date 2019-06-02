<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("dbcon.php");
    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");
    error_log("TST", 0);
    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {
        $seller_id=$_POST["seller_id"];
        // 안드로이드 코드의 posParameters 변수에 적어 준 이름을 가지고 값을 전달받습니다.
       try {
                $stmt = $con->prepare("SELECT avg(rate) rate FROM rate group by seller_id having seller_id=:seller_id");
                $stmt->bindParam(":seller_id", $seller_id);
                $stmt->execute();
                $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
                $response = array();
                $response["success"] = false;
                if ($stmt->rowCount() > 0) {
      			$response["success"] = true;
                        $response["rate"] = $userRow["rate"];
                 }
                    
                header("Content-Type: application/jason; charset-utf8");
                echo json_encode($response, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
            } catch (PDOException $e) {
                 die("Database error : " .$e->getMessage());
            }
        
    }
            
?> 
