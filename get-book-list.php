<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("dbcon.php");

    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");

    error_log("TST", 0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {

        $user_id=$_POST["user_id"];
	$state=$_POST["state"];


        // 안드로이드 코드의 posParameters 변수에 적어 준 이름을 가지고 값을 전달받습니다.
        if (empty($user_id))
            $errMSG ="사용자 아이디를 넘겨주세요.";

        if (!isset($errMSG)) {

            try {
		
		if ($state == 1)		// BookMark
		    $stmt = $con->prepare("SELECT * FROM book_mark WHERE member_id=:member_id");
		else if ($state == 2)	// Sell
		    $stmt = $con->prepare("SELECT * FROM register_book WHERE seller_id=:member_id");
		else if ($state == 3)	// Buy
		    $stmt = $con->prepare("SELECT * FROM trade WHERE buyer_id=:member_id");
	
                $stmt->bindParam(":member_id", $user_id);
                $stmt->execute();
		
 		// 사용자와 맞는 레코드가 있다면
                if ($stmt->rowCount() > 0) {
		    $whole_data = array();
		
		    // 모든 책의 정보를 저장한다.
		    while($userRow=$stmt->fetch(PDO::FETCH_ASSOC)) {
			$book_data = array();

//		    	$book_data["success"] = true;
//		    	$response["register_id"] = $userRow["book_register_id"];
//		    	$response["user_id"] = $userRow["member_id"];

			extract($userRow);
			
			// 책 등록 정보 가져오기		
		    	$register_stmt = $con->prepare("SELECT * FROM register_book WHERE book_register_id=:register_id LIMIT 1");
		    	$register_stmt->bindParam(":register_id", $userRow["book_register_id"]);
		    	$register_stmt->execute();
		    	$register_row = $register_stmt->fetch(PDO::FETCH_ASSOC);
			
		    	if ($register_stmt->rowCount() > 0) {
			    $book_data["register_id"] = $register_row["book_register_id"];
			    $book_data["selling_price"] = $register_row["ISBN"];
			    
			    // 책 정보 가져오기
			    $book_stmt = $con->prepare("SELECT * FROM book WHERE ISBN=:isbn LIMIT 1");
			    $book_stmt->bindParam(":isbn", $register_row["ISBN"]);
			    $book_stmt->execute();
			    $book_row = $book_stmt->fetch(PDO::FETCHASSOC);
			
			    if ($book_stmt->rowCount() > 0) {
				$book_data["book_name"] = $book_row["name"];
				$book_data["author"] = $book_row["author"];
				$book_data["publisher"] = $book_row["publisher"];
				$book_data["original_price"] = $book_row["price"];
			    }
		    	}
			array_push($whole_data, $book_data);		    

		    }

		    // Json 형식으로 값 전달
		    header("Content-Type: application/jason; charset-utf8");
		    $json = json_encode(array("bookmark"=>$whole_data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
		    echo $json;

		}	
            } catch (PDOException $e) {
                 die("Database error : " .$e.getMessage());
            }

        }

    }
?>       
