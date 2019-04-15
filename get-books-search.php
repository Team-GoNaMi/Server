<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("dbcon.php");

    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");

    error_log("TST", 0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {

//	$num = $_POST["num"];
//	$num = 0;

        // 안드로이드 코드의 posParameters 변수에 적어 준 이름을 가지고 값을 전달받습니다.
//        if (empty($num))
//            $errMSG ="개수를 받아와 주세요.";

        if (!isset($errMSG)) {

            try {
//		$start = $num * 20;
		
//		$stmt = $con->prepare("SELECT * FROM register_book ORDER BY register_id ASC LIMIT 20 OFFSET $start");
		$stmt = $con->prepare("SELECT * FROM register_book ORDER BY book_register_id ASC");
		$stmt->execute();
		
		

 		// 레코드 20개만큼 돌면서
		if ($stmt->rowCount() > 0) {
		    $whole_data = array();
		
		    // 모든 책의 정보를 저장한다.
		    while($userRow=$stmt->fetch(PDO::FETCH_ASSOC)) {
			$book_data = array();

		    	$book_data["success"] = true;
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
			    $book_data["selling_price"] = $register_row["price"];
			    
			    // 책 정보 가져오기
			    $book_stmt = $con->prepare("SELECT * FROM book WHERE ISBN=:isbn LIMIT 1");
			    $book_stmt->bindParam(":isbn", $register_row["ISBN"]);
			    $book_stmt->execute();
			    $book_row = $book_stmt->fetch(PDO::FETCH_ASSOC);
			
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
		    $json = json_encode(array("search"=>$whole_data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
		    echo $json;

		}
	
            } catch (PDOException $e) {
                 die("Database error : " .$e.getMessage());
            }

        }

    }
?>       
