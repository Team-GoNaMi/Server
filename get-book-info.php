<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("dbcon.php");

    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");

    error_log("TST", 0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android ) {

        $register_id=$_POST["register_id"];


        // 안드로이드 코드의 posParameters 변수에 적어 준 이름을 가지고 값을 전달받습니다.
        if (empty($register_id))
            $errMSG = "책 등록번호를를 넘겨주세요.";

        if (!isset($errMSG)) {

            try {

                $register_stmt = $con->prepare("SELECT * FROM register_book NATURAL JOIN book WHERE book_register_id=:register_id LIMIT 1");
                $register_stmt->bindParam(":register_id", $register_id);
                $register_stmt->execute();
				$bookInfoRow = $register_stmt->fetch(PDO::FETCH_ASSOC); 

                $book_data = array();
                $book_data["success"] = false;

                if ($register_stmt->rowCount() > 0) {
					$book_data["success"] = true;
					$book_data["register_id"] = $bookInfoRow["book_register_id"];
					$book_data["ISBN"] = $bookInfoRow["ISBN"];
					$book_data["seller_id"] = $bookInfoRow["seller_id"];
					$book_data["selling_price"] = $bookInfoRow["selling_price"];
					$book_data["memo"] = $bookInfoRow["memo"];
					$book_data["buy_avail"] = $bookInfoRow["buy_avail"];
					$book_data["underline"] = $bookInfoRow["underline"];
					$book_data["writing"] = $bookInfoRow["writing"];
					$book_data["cover"] = $bookInfoRow["cover"];
					$book_data["damage_page"] = $bookInfoRow["damage_page"];
					
					$book_data["book_name"] = $bookInfoRow["name"];
					$book_data["author"] = $bookInfoRow["author"];
					$book_data["publisher"] = $bookInfoRow["publisher"];
					$book_data["original_price"] = $bookInfoRow["original_price"];
					$book_data["publish_date"] = $bookInfoRow["publish_date"];

				}
                    
                header("Content-Type: application/jason; charset-utf8");
                echo json_encode($book_data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

            } catch (PDOException $e) {
                 die("Database error : " .$e.getMessage());
            }

        }

    }
?>       
