<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    include("dbcon.php");

    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");

    error_log("TST", 0);

    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android ) {

        $user_id=$_POST["user_id"];
//		$state=$_POST["state"];


        // 안드로이드 코드의 posParameters 변수에 적어 준 이름을 가지고 값을 전달받습니다.
        if (empty($user_id))
            $errMSG ="사용자 아이디를 넘겨주세요.";

        if (!isset($errMSG)) {

            try {
				$stmt = $con->prepare("SELECT * FROM book_mark WHERE member_id=:member_id ORDER BY book_register_id DESC");
/*	
				if ($state == "1")		// BookMark
					$stmt = $con->prepare("SELECT * FROM book_mark WHERE member_id=:member_id");
				else if ($state == "2")	// Sell
					$stmt = $con->prepare("SELECT * FROM register_book WHERE seller_id=:member_id");
				else if ($state == 3)	// Buy
					$stmt = $con->prepare("SELECT * FROM trade WHERE buyer_id=:member_id");
*/	
                $stmt->bindParam(":member_id", $user_id);
                $stmt->execute();
				
				$whole_data = array();
				
				// 사용자와 맞는 레코드가 있다면
                if ($stmt->rowCount() > 0) {
		
					// 모든 책의 정보를 저장한다.
					while($userRow=$stmt->fetch(PDO::FETCH_ASSOC)) {
						$book_data = array();

//						extract($userRow);
			
						// 책 등록 정보 및 책 정보 가져오기		
						$book_stmt = $con->prepare("SELECT * FROM register_book NATURAL JOIN book  WHERE book_register_id=:register_id LIMIT 1");
						$book_stmt->bindParam(":register_id", $userRow["book_register_id"]);
						$book_stmt->execute();
						$book_row = $book_stmt->fetch(PDO::FETCH_ASSOC);
			
						if ($book_stmt->rowCount() > 0) {
							$register_id = $book_row["book_register_id"];
							$book_data["register_id"] = $register_id;
							$book_data["selling_price"] = $book_row["selling_price"];
							$book_data["book_name"] = $book_row["name"];
							$book_data["author"] = $book_row["author"];
							$book_data["publisher"] = $book_row["publisher"];
							$book_data["original_price"] = $book_row["original_price"];
							$book_data["book_image"] = $book_row["book_image"];

							// BookMark
							$bookmark_stmt = $con->prepare("SELECT * FROM book_mark WHERE book_register_id=:register_id AND member_id=:user_id");
							$bookmark_stmt->bindParam(":register_id", $register_id);
							$bookmark_stmt->bindParam(":user_id", $user_id);
							$bookmark_stmt->execute();

						    if ($bookmark_stmt->rowCount() > 0)
								$book_data["bookmark"] = true;
							else
								$book_data["bookmark"] = false;


							// 학교
							$school_stmt = $con->prepare("SELECT * FROM book_school WHERE book_register_id=:register_id");
							$school_stmt->bindParam(":register_id", $register_id);
							$school_stmt->execute();

							$school = "";
							while($schoolRow = $school_stmt->fetch(PDO::FETCH_ASSOC)) {
								$school = $school . $schoolRow["school"] . ", ";
							}
							$school = substr($school, 0, -1);
							$school = substr($school, 0, -1);

							$book_data["school"] = $school;

/*
							// 이미지
							$image_stmt = $con->prepare("SELECT * FROM book_photo WHERE book_register_id=:register_id");
							$image_stmt->bindParam(":register_id", $register_id);
							$image_stmt->execute();

							$image = "";
							while($imageRow = $image_stmt->fetch(PDO::FETCH_ASSOC)) {
								$image = $image . $imageRow["photo"] . ",";
							}
							$image = substr($image, 0, -1);

							$book_data["book_images"] = $image;
*/
						}	

						array_push($whole_data, $book_data);		    

					}		
				}
				// Json 형식으로 값 전달
//					header("Content-Type: application/jason; charset-utf8");
				$json = json_encode(array("book_list"=>$whole_data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
				echo $json;
	
			} catch (PDOException $e) {
				die("Database error : " .$e->getMessage());
			}
		}
    }
?>       
