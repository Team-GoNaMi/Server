<?php
    error_reporting(E_ALL); 
    ini_set("display_errors",1); 
    include("dbcon.php");
    $android = strpos($_SERVER["HTTP_USER_AGENT"], "Android");
    error_log("TST",0);
    if( (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["submit"])) || $android )
    {
        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
        $bb_id=$_POST["bb_id"];
        $date=$_POST["date"];
        $book_register_id=$_POST["book_register_id"];
        $state=$_POST["state"];


        try{
                // SQL문을 실행하여 데이터를 MySQL 서버의 reserve_bb 테이블에 저장합니다.
                
                $stmt = $con->prepare("INSERT INTO reserve_bb(box_id, book_register_id, date, state) VALUES(:bb_id, :book_register_id, :date, :state)");
                $stmt->bindParam(":bb_id", $bb_id);
                $stmt->bindParam(":book_register_id", $book_register_id);
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":state", $state);
                if($stmt->execute())
                {
                    $successMSG = "북박스가 예약되었습니다.";
                }
                else
                {
                    $errMSG = "사용자 추가 에러";
                }
            } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
            }
	

       
    }
?>
