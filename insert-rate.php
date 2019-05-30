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
	$book_register_id=$_POST["book_register_id"];
        $seller_id=$_POST["seller_id"];
		
	try{
	    
	    $rate_stmt = $con->prepare("INSERT INTO rate(book_register_id, seller_id, rate) VALUES (:register_id, :seller_id, :rate)");	
	    $rate_stmt->bindParam(":rate", $rate);
	    $rate_stmt->bindParam(":seller_id", $seller_id);
	    $rate_stmt->bindParam(":register_id", $book_register_id);
	    $rate_stmt->execute();
	       
	    $update_stmt = $con->prepare("UPDATE trade SET state=5 WHERE book_register_id=:book_register_id");
	    $update_stmt->bindParam(":book_register_id", $book_register_id);
	    $update_stmt->execute();
	
	} catch(PDOException $e) {
            die("Database error: " . $e->getMessage()); 
        }
       
    }
?>
