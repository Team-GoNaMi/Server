<?php
    error_reporting(E_ALL); 
    ini_set("display_errors",1); 
    
    include("dbcon.php");
    include ("send-notification.php");

    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");
    error_log("TST",0);
    
    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {
        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
        $rate=$_POST["rate"];
	$book_register_id=$_POST["book_register_id"];
        $seller_id=$_POST["seller_id"];

	echo $book_register_id;	
	try{
	    
	    $rate_stmt = $con->prepare("INSERT INTO rate(book_register_id, seller_id, rate) VALUES (:register_id, :seller_id, :rate)");	
	    $rate_stmt->bindParam(":rate", $rate);
	    $rate_stmt->bindParam(":seller_id", $seller_id);
	    $rate_stmt->bindParam(":register_id", $book_register_id);
	    $rate_stmt->execute();
	       
	    $update_stmt = $con->prepare("UPDATE trade SET state=5 WHERE book_register_id=:book_register_id");
	    $update_stmt->bindParam(":book_register_id", $book_register_id);
	    $update_stmt->execute();

	    // fcm
            $push_stmt = $con->prepare("SELECT seller_id, Token
                                        FROM register_book JOIN token ON register_book.seller_id=token.member_id
                                        WHERE book_register_id=:register_id LIMIT 1");
            $push_stmt->bindParam(":register_id", $book_register_id);
            $push_stmt->execute();

            $row = $push_stmt->fetch(PDO::FETCH_ASSOC);
            $seller_token = "";

            if ($push_stmt->rowCount() > 0) {
                $seller_id = $row["seller_id"];
                $seller_token = $row["Token"];
            }

            $mTitle = "돈 받을 준비 하세요~!";
            $mMessage = "계좌를 입력해 주세요";


            $input_data = array("title" =>$mTitle, "body" => $mMessage);
            $result = send_notification($seller_token, $input_data);

            echo $result;

	
	} catch(PDOException $e) {
            die("Database error: " . $e->getMessage()); 
        }
       
    }
?>
