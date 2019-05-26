<?php
    error_reporting(E_ALL); 
    ini_set("display_errors",1); 
    include("dbcon.php");
    
	$android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");
    error_log("TST",0);
    
	if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {
        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
        $bb_location=$_POST["bb_location"];
        $date=$_POST["date"];
        $book_register_id=$_POST["book_register_id"];


        try{
                // SQL문을 실행하여 데이터를 MySQL 서버의 reserve_bb 테이블에 저장합니다.
			
            // 남는 Table 찾기
			$find_stmt = $con->prepare("SELECT b.box_id
					FROM (SELECT *
						FROM book_box
						WHERE box_id LIKE '$bb_location%') b
					LEFT JOIN
					(SELECT *
					 FROM reserve_bb
					 WHERE date=:date) r
					ON b.box_id=r.box_id
					WHERE r.date IS NULL LIMIT 1");
			
//			$find_stmt->bindParam(":location", $bb_location);
			$find_stmt->bindParam(":date", $date);
			$find_stmt->execute();

			$bb_id = "";
			$result = "";
			if ($find_stmt->rowCount() > 0) {
				$bb_row = $find_stmt->fetch(PDO::FETCH_ASSOC);
				
				$bb_id = $bb_row["box_id"];


				// Table에 저장
				$reserve_stmt = $con->prepare("INSERT INTO reserve_bb(box_id, book_register_id, date) VALUES(:bb_id, :book_register_id, :date)");
				$reserve_stmt->bindParam(":bb_id", $bb_id);
				$reserve_stmt->bindParam(":book_register_id", $book_register_id);
				$reserve_stmt->bindParam(":date", $date);
				$reserve_stmt->execute();

				// trade table에 state 2로 변경해 줘야함
				$update_stmt = $con->prepare("UPDATE trade SET state=2 WHERE book_register_id=:book_register_id");
				$update_stmt->bindParam(":book_register_id", $book_register_id);
				$update_stmt->execute();

				$result = "success, " . $bb_id;
			}
			else {
				$result = "fail";
			}
			echo json_encode($result, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
			
        } catch(PDOException $e) {
            die("Database error: " . $e->getMessage()); 
        }
	
     
    }
?>
