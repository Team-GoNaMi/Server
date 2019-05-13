<?php
    error_reporting(E_ALL); 
    ini_set("display_errors",1); 
    include("dbcon.php");
    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");
    error_log("TST",0);
    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {
        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
        $bank_info=$_POST["bank_info"];
        $account_num=$_POST["account_num"];
        $register_id=$_POST["register_id"];
		
        try{
			$trade_stmt = $con->prepare("UPDATE trade SET bank=:bank_info, account_num=:account_num, state=6 WHERE book_register_id=:register_id");
			$trade_stmt->bindParam(":bank_info", $bank_info);
			$trade_stmt->bindParam(":account_num", $account_num);
			$trade_stmt->bindParam(":register_id", $register_id);
			$trade_stmt->execute();
		
			
        } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
        }
       
    }
?>
