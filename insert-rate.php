<?php
    error_reporting(E_ALL); 
    ini_set("display_errors",1); 
    include("dbcon.php");
    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");
    error_log("TST",0);
    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {
        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
        $rate=$_POST["rate"];
        $register_id=$_POST["register_id"];
		
        try{
			$trade_stmt = $con->prepare("UPDATE trade SET rate=:rate WHERE book_register_id=:register_id");
			$trade_stmt->bindParam(":rate", $rate);
			$trade_stmt->bindParam(":account_num", $account_num);
			$trade_stmt->execute();
			
        } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
        }
       
    }
?>
