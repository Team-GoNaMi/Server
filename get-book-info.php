<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("dbcon.php");

    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");

    error_log("TST", 0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {

        $register_id=$_POST["register_id"];


        // 안드로이드 코드의 posParameters 변수에 적어 준 이름을 가지고 값을 전달받습니다.
        if (empty($register_id))
            $errMSG = "책 등록번호를를 넘겨주세요.";

        if (!isset($errMSG)) {

            try {

                $register_stmt = $con->prepare("SELECT * FROM register_book WHERE book_register_id=:register_id LIMIT 1");
                $register_stmt->bindParam(":register_id", $register_id);
                $register_stmt->execute();
		$userRow=$register_stmt->fetch(PDO::FETCH_ASSOC); 

                $response = array();
                $response["success"] = false;

                if ($register_stmt->rowCount() > 0) {
		    $response["success"] = true;
		    $response["register_id"] = $userRow["book_register_id"];
		    $response["ISBN"] = $userRow["ISBN"];
		    $response["seller_id"] = $userRow["seller_id"];
		    $response["selling_price"] = $userRow["price"];
		    $response["memo"] = $userRow["memo"];
		    $response["buy_avail"] = $userRow["buy_avail"];
		    $response["underline"] = $userRow["underline"];
		    $response["writing"] = $userRow["writing"];
		    $response["cover"] = $userRow["cover"];
		    $response["damage_page"] = $userRow["damage_page"];
		
		    $book_stmt = $con->prepare("SELECT * FROM book WHERE ISBN=:isbn LIMIT1");
		    $book_stmt->bindParam(":isbn", $userRow["ISBN"]);
		    $book_stmt->execute();
		    $book_row = $book_stmt->fetch(PDO::FETCH_ASSOC);

		    if ($book_stmt->rowCount() > 0) {
			$response["book_name"] = $book_row["name"];
			$response["author"] = $book_row["author"];
			$response["publisher"] = $book_row["publisher"];
			$response["original_price"] = $book_row["price"];
			$response["publish_date"] = $book_row["publish_date"];
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
