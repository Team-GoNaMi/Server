<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	include("dbcon.php");

	$android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");

	error_log("TST", 0);

	if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android ) {

		$searchWord = $_POST["searchWord"];
		$searchSchool = $_POST["searchSchool"];
		
		try {
			// 검색어가 없고 학교가 전체이면
			if ((empty($searchWord)) && ($searchSchool == "all")) {

				$basic_stmt = $con->prepare("SELECT * FROM register_book NATURAL JOIN book WHERE buy_avail=1 ORDER BY book_register_id DESC");

			}	
		
			// 검색어가 없고 학교가 정해지면
			else if ((empty($searchWord)) && ($searchSchool != "all")){
	
				$basic_stmt = $con->prepare("SELECT * FROM book_school NATURAL JOIN register_book NATURAL JOIN book WHERE school=:school and buy_avail=1 ORDER BY book_register_id DESC");
				$basic_stmt->bindParam(":school", $searchSchool);

			}
		
			// 검색어가 있고 학교가 전체이면
			else if ((isset($searchWord)) && ($searchSchool == "all")) {
			
				$basic_stmt = $con->prepare("SELECT * FROM register_book NATURAL JOIN book WHERE name LIKE '%$searchWord%' and buy_avail=1 ORDER BY book_register_id DESC");
					
			}
		
			// 검색어가 있고 학교가 정해지면
			else if ((isset($searchWord)) && ($searchSchool != "all")) {
			
				$basic_stmt = $con->prepare("SELECT * FROM book_school NATURAL JOIN register_book NATURAL JOIN book WHERE name LIKE '%$searchWord%' AND school=:school and buy_avail=1 ORDER BY book_register_id DESC");
				$basic_stmt->bindParam(":school", $searchSchool);
		
			}

			$basic_stmt->execute();

			$whole_data = array();

			if($basic_stmt->rowCount() > 0) {

			//					$whole_data = array();

				while($userRow=$basic_stmt->fetch(PDO::FETCH_ASSOC)) {
					$book_data = array();
					$register_id = $userRow["book_register_id"];
	
					$book_data["register_id"] = $register_id;
					$book_data["selling_price"] = $userRow["selling_price"];
					$book_data["book_name"] = $userRow["name"];
					$book_data["author"] = $userRow["author"];
					$book_data["publisher"] = $userRow["publisher"];
					$book_data["original_price"] = $userRow["original_price"];
					$book_data["book_image"] = $userRow["book_image"];


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
					// 이미지 추가
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

					array_push($whole_data, $book_data);
				}

			}


			//				header("Content-Type : application/jason; charset-utf8");	
			$json = json_encode(array("basic"=>$whole_data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
			echo $json;

		} catch (PDOException $e) {
			die("Database error : " .$e.getMessage());
		}
	
	}

//		$stmt = $con->prepare("SELECT * FROM register_book ORDER BY register_id ASC LIMIT 20 OFFSET $start");
//		$stmt = $con->prepare("SELECT * FROM register_book ORDER BY book_register_id ASC");
//		    	$register_stmt = $con->prepare("SELECT * FROM register_book WHERE book_register_id=:register_id LIMIT 1");
//			    $book_stmt = $con->prepare("SELECT * FROM book WHERE ISBN=:isbn LIMIT 1");

?>       
